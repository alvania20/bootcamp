<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    // --- ADMIN METHODS ---
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::with('category')->latest()->get()
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', ['categories' => Category::all()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request->file('image'));
        }
        
        // Menambahkan slug agar URL produk rapi
        $validated['slug'] = Str::slug($validated['name']);

        Product::create($validated);
        
        // Pastikan route ini sesuai dengan route name di web.php
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::all()
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product->id);

        DB::transaction(function () use ($request, $product, &$validated) {
            if ($request->hasFile('image')) {
                $this->deleteImage($product->image);
                $validated['image'] = $this->uploadImage($request->file('image'));
            }
            
            $validated['slug'] = Str::slug($validated['name']);
            $product->update($validated);
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {
            $this->deleteImage($product->image);
            $product->delete();
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }

    // --- PUBLIC METHODS ---
    public function katalog(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        match ($request->sort) {
            'harga_tertinggi' => $query->orderBy('price', 'desc'),
            'harga_terendah'  => $query->orderBy('price', 'asc'),
            default           => $query->latest(),
        };

        return view('products.katalog', [
            'products'   => $query->paginate(9)->withQueryString(),
            'categories' => Category::all()
        ]);
    }

    public function show(Product $product): View
    {
        $product->increment('views');
        return view('products.show', compact('product'));
    }

    // --- HELPER METHODS ---
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
        $imageName = time() . '_' . $name . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
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