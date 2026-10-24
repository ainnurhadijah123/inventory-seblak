<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create(['name' => $request->name]);
        return back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function destroy(Category $category)
    {
        // Cegah penghapusan jika kategori masih dipakai oleh produk
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Gagal! Kategori ini masih memiliki produk.');
        }
        
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}