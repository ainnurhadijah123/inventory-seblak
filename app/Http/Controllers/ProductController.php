<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->input('search');

    $products = Product::when($search, function ($query, $search) {
        return $query->where('name', 'like', "%{$search}%");
    })->get();

    return view('products.index', compact('products'));
    }

    public function menuIndex(Request $request)
    {
        $query = Product::with('category')->where('stock', '>', 0);
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $query->get();
        $autoAddId = $request->query('add');

        return view('menu.index', compact('products', 'autoAddId'));
    }

    public function create()
    {
        if (auth()->check() && auth()->user()->role === 'staff') {
            return redirect()->route('products.index')->with('error', 'Staff hanya memiliki akses untuk melihat data produk.');
        }
        
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'staff') {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'selling_price' => $request->price,
            'stock' => $request->stock,
            // Perbaikan: Menambahkan default 0 agar tidak ditolak database, dan memperpendek SKU
            'purchase_price' => $request->purchase_price ?? 0, 
            'minimum_stock' => $request->minimum_stock ?? 0,
            'sku' => 'PRD-' . rand(10000, 99999), 
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (auth()->check() && auth()->user()->role === 'staff') {
            return redirect()->route('products.index')->with('error', 'Staff tidak diizinkan mengubah data produk.');
        }

        $product = Product::findOrFail($id);
        $categories = Category::all();
        
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'selling_price' => $request->price,
            'stock' => $request->stock,
            'purchase_price' => $request->purchase_price ?? $product->purchase_price,
            'minimum_stock' => $request->minimum_stock ?? $product->minimum_stock,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->check() && auth()->user()->role === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $product = Product::findOrFail($id);

        $isUsedInSales = \Illuminate\Support\Facades\DB::table('sale_items')
            ->where('product_id', $id)
            ->exists();

        if ($isUsedInSales) {
            $product->update(['stock' => 0]);
            return redirect()->route('products.index')->with('success', 'Produk memiliki riwayat transaksi, sehingga stoknya otomatis diubah menjadi 0 (dinonaktifkan).');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}