<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::query();

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        $products = $query->latest()->paginate(9)->withQueryString();
        return view('products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        // Pastikan model Category sudah di-import
        return view('products.create', ['categories' => Category::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::random(5) . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $validated['image'] = $imageName;
        }

        $validated['slug'] = Str::slug($validated['name']);
        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        return view('products.edit', [
            'product'    => Product::findOrFail($id),
            'categories' => Category::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string', // KOREKSI: Tambahkan field ini
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && File::exists(public_path('img/' . $product->image))) {
                File::delete(public_path('img/' . $product->image));
            }
            $imageName = time() . '_' . Str::random(5) . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $validated['image'] = $imageName;
        }

        $validated['slug'] = Str::slug($validated['name']);
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && File::exists(public_path('img/' . $product->image))) {
            File::delete(public_path('img/' . $product->image));
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}