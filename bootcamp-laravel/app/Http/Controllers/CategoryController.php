<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     * View diarahkan ke 'admin.categories.index' sesuai lokasi file Anda.
     */
    public function index() {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories')); 
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:categories,name']);
        
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
        
        return back()->with('success', 'Kategori berhasil ditambah!');
    }

    /**
     * Menampilkan form edit.
     * Pastikan file view ini juga berada di resources/views/admin/categories/edit.blade.php
     */
    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function destroy(Category $category) {
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}