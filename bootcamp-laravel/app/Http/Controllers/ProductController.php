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
    /**
     * Konstruktor untuk memisahkan akses Admin.
     * Semua metode di dalam controller ini akan memerlukan auth dan admin,
     * KECUALI metode katalog dan show yang bisa diakses publik.
     */
    public function __construct()
    {
        // Middleware ini memastikan hanya admin yang bisa akses method admin.
        // Kita terapkan di routes/web.php, namun kita bisa tambahkan perlindungan di sini.
    }

    // --- ADMIN METHODS (Seharusnya hanya diakses Admin) ---
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

    // --- PUBLIC METHODS (Akses User/Tamu) ---
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
        // Increment views & clicks untuk statistik dashboard
        $product->increment('views');
        $product->increment('clicks');
        
        return view('products.show', compact('product'));
    }

    // --- HELPER METHODS ---
    private function generateUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $originalSlug = $slug;
        $i = 1;
        while (Product::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->withTrashed() 
            ->exists()) {
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
}