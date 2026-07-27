<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
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
        $file = $request->file('image');
        $directory = public_path('uploads/articles');
        File::ensureDirectoryExists($directory);
        $filename = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);
        return 'uploads/articles/' . $filename;
    }

    private function deleteImage(?string $image): void
    {
        if ($image && File::exists(public_path($image))) {
            File::delete(public_path($image));
        }
    }
}