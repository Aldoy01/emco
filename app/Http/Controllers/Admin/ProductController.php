<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', ['products' => Product::with('category')->latest()->paginate(20)]);
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        Product::create($this->validated($request));
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product);
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product->image);
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_code' => 'required|string|max:120',
            'product_name' => 'required|string|max:160',
            'short_description' => 'nullable|string|max:500',
            'features_text' => 'nullable|string',
            'specifications_text' => 'nullable|string',
            'price_usd' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'final_price_usd' => 'nullable|numeric|min:0',
            'price_note' => 'nullable|string|max:500',
            'datasheet_file' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive,by_request,discontinued',
            'is_featured' => 'nullable|boolean',
        ]);

        $data['price_usd'] = $request->filled('price_usd') ? (float) $request->input('price_usd') : (float) ($product->price_usd ?? 0);
        $data['discount_percent'] = $request->filled('discount_percent') ? (float) $request->input('discount_percent') : (float) ($product->discount_percent ?? 0);
        $data['slug'] = Str::slug($data['product_name']);
        $data['final_price_usd'] = round(((float) $data['price_usd']) * (100 - (float) $data['discount_percent']) / 100, 2);
        $data['features'] = collect(preg_split('/\r\n|\r|\n/', $request->features_text ?? ''))->filter()->values()->all();
        $data['specifications'] = collect(preg_split('/\r\n|\r|\n/', $request->specifications_text ?? ''))->filter()->values()->all();
        $data['is_featured'] = $request->boolean('is_featured');
        unset($data['features_text'], $data['specifications_text']);

        if ($request->hasFile('image')) {
            if ($product) {
                $this->deleteImage($product->image);
            }
            $data['image'] = $this->storeImage($request);
        } else {
            unset($data['image']);
        }

        return $data;
    }

    private function storeImage(Request $request): string
    {
        $file = $request->file('image');
        $directory = base_path('uploads/products');
        File::ensureDirectoryExists($directory);
        $filename = Str::slug($request->product_name) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);
        return 'uploads/products/' . $filename;
    }

    private function deleteImage(?string $image): void
    {
        if (!$image) {
            return;
        }

        $path = public_path($image);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
