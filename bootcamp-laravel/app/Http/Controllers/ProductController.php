<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', [
            'products' => Product::with('category')->latest()->get()
        ]);
    }

    public function katalog(Request $request)
    {
        $categories = Category::all();
        $query = Product::query();

        // 1. Fitur Pencarian
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Kategori
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // 3. Sorting
        match ($request->sort) {
            'harga_tertinggi' => $query->orderBy('price', 'desc'),
            'harga_terendah'  => $query->orderBy('price', 'asc'),
            default           => $query->latest(),
        };

        return view('products.katalog', [
            'products'   => $query->paginate(9)->withQueryString(),
            'categories' => $categories
        ]);
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create', ['categories' => Category::all()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request->file('image'));
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::all()
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);

        DB::transaction(function () use ($request, $product, &$validated) {
            if ($request->hasFile('image')) {
                $this->deleteImage($product->image);
                $validated['image'] = $this->uploadImage($request->file('image'));
            }

            $product->update($validated);
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            $this->deleteImage($product->image);
            $product->delete();
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    // --- Private Helper Methods ---

    private function validateProduct(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => ($id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
    }

    private function uploadImage($file): string
    {
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $imageName = time() . '_' . $name . '_' . Str::random(5) . '.' . $file->extension();
        $file->move(public_path('img'), $imageName);
        return $imageName;
    }

    private function deleteImage(?string $imageName): void
    {
        if ($imageName && File::exists(public_path('img/' . $imageName))) {
            File::delete(public_path('img/' . $imageName));
        }
    }
}