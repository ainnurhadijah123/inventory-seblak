<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function history()
    {
        $orders = Sale::with('user')->latest()->get();
        return view('admin.transactions', compact('orders'));
    }

    public function confirm(Request $request)
{
    // Tangkap semua input (untuk diteruskan sebagai hidden input nanti)
    $orderData = $request->all();

    // Olah data keranjang untuk ditampilkan
    $items = $request->input('items', []);
    $selectedItems = [];
    $totalItemsPrice = 0;
    
    foreach ($items as $id => $qty) {
        if ($qty > 0) {
            $product = \App\Models\Product::find($id);
            if ($product) {
                // Sesuaikan 'price' atau 'selling_price' dengan kolom database Anda
                $price = $product->price ?? $product->selling_price ?? 0;
                $subtotal = $price * $qty;
                
                $selectedItems[] = [
                    'name' => $product->name,
                    'price' => $price,
                    'qty' => $qty,
                    'subtotal' => $subtotal
                ];
                $totalItemsPrice += $subtotal;
            }
        }
    }

    // Jika kosong, kembalikan ke menu
    if (empty($selectedItems)) {
        return back()->with('error', 'Pilih minimal satu menu sebelum memesan.');
    }

    // Hitung level pedas
    $spicyLevel = $request->input('spicy_level', 0);
    $spicyCost = 0;
    if ($spicyLevel == 2) $spicyCost = 1000;
    elseif ($spicyLevel == 3) $spicyCost = 2000;

    $grandTotal = $totalItemsPrice + $spicyCost;
    $customerName = $request->input('customer_name', null);

    return view('orders.confirm', compact('orderData', 'selectedItems', 'spicyLevel', 'spicyCost', 'grandTotal', 'customerName'));
}
}