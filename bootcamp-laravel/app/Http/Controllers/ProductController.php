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

        DB::transaction(function () use ($request, &$validated) {
            if ($request->hasFile('image')) {
                $validated['image'] = $this->uploadImage($request->file('image'));
            }
            
            // Generate slug unik sebelum insert
            $validated['slug'] = $this->generateUniqueSlug(Str::slug($validated['name']));

            Product::create($validated);
        });
        
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
            
            // Generate slug baru hanya jika nama produk berubah
            if ($product->name !== $validated['name']) {
                $validated['slug'] = $this->generateUniqueSlug(Str::slug($validated['name']), $product->id);
            }
            
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

    // --- HELPER METHODS ---
    
    private function generateUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $originalSlug = $slug;
        $i = 1;

        // Pengecekan slug unik dengan mempertimbangkan data yang ter-soft delete
        while (Product::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->withTrashed() 
            ->exists()) {
            // Append angka, jika sudah > 5 kali percobaan, gunakan random string agar slug tetap unik
            $slug = ($i > 5) ? $originalSlug . '-' . Str::random(5) : $originalSlug . '-' . $i++;
        }

        return $slug;
    }

    private function validateProduct(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
    }

    private function uploadImage($file): string
    {
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        return time() . '_' . $name . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
    }

    private function deleteImage(?string $imageName): void
    {
        if ($imageName && File::exists(public_path('img/' . $imageName))) {
            File::delete(public_path('img/' . $imageName));
        }
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
}