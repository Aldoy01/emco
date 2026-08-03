<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        return view('admin.articles.index', [
            'articles' => Article::latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.articles.form', ['article' => new Article()]);
    }

    public function store(Request $request)
    {
        Article::create($this->validated($request));
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $article->update($this->validated($request, $article));
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $this->deleteImage($article->image);
        $article->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    public function uploadImage(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $this->storeArticleImage($data['file'], 'artikel-editor');

        return response()->json([
            'location' => asset($path),
        ]);
    }

    private function validated(Request $request, ?Article $article = null): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:180',
            'category' => 'nullable|string|max:80',
            'author_name' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $data['slug'] = $this->uniqueSlug($data['title'], $article);
        $data['author_name'] = $data['author_name'] ?: ($request->user()->name ?? 'Admin EMKO');
        $data['published_at'] = $data['status'] === 'published'
            ? ($data['published_at'] ?? now())
            : null;

        if ($request->hasFile('image')) {
            if ($article) {
                $this->deleteImage($article->image);
            }
            $data['image'] = $this->storeImage($request);
        } else {
            unset($data['image']);
        }

        return $data;
    }

    private function uniqueSlug(string $title, ?Article $article = null): string
    {
        $base = Str::slug($title) ?: 'artikel';
        $slug = $base;
        $counter = 2;

        while (Article::where('slug', $slug)->when($article, fn ($query) => $query->where('id', '!=', $article->id))->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    private function storeImage(Request $request): string
    {
        return $this->storeArticleImage($request->file('image'), $request->title);
    }

    private function storeArticleImage(UploadedFile $file, string $name): string
    {
        $directory = config('emko.article_upload_path', public_path('uploads/articles'));
        File::ensureDirectoryExists($directory);
        $filename = Str::slug($name) . '-' . time() . '-' . Str::random(4) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return trim(config('emko.article_upload_url', 'uploads/articles'), '/') . '/' . $filename;
    }

    private function deleteImage(?string $image): void
    {
        if (!$image) {
            return;
        }

        $paths = [
            public_path($image),
            rtrim(config('emko.article_upload_path', public_path('uploads/articles')), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($image),
        ];

        foreach (array_unique($paths) as $path) {
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }
}
