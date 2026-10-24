<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    // Menampilkan riwayat barang masuk
    public function index(Request $request)
    {
    $search = $request->input('search');

    $purchases = Purchase::with(['supplier', 'user'])
        ->when($search, function ($query, $search) {
            return $query->whereHas('supplier', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })->latest()->get();

    return view('purchases.index', compact('purchases'));
    }

    // Menampilkan form tambah stok
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('purchases.create', compact('products', 'suppliers'));
    }

    // Menyimpan data stok masuk & memperbarui stok produk
    public function store(Request $request)
    {
        // 1. Validasi Input (Menggunakan nama kolom yang baru dengan pesan Bahasa Indonesia)
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'supplier_name' => 'required|string|max:255', // Validasi input teks nama agen
            'quantity'      => 'required|integer|min:1',
            'total_cost'    => 'required|numeric|min:0',
        ], [
            'product_id.required'    => 'Mohon pilih produk yang ingin di-restock.',
            'product_id.exists'      => 'Produk yang dipilih tidak ditemukan.',
            'supplier_name.required' => 'Nama supplier atau agen tidak boleh kosong.',
            'quantity.required'      => 'Jumlah (Qty) wajib diisi.',
            'quantity.min'           => 'Jumlah minimal adalah 1.',
            'total_cost.required'    => 'Total biaya kulakan wajib diisi.',
            'total_cost.min'         => 'Total biaya tidak boleh negatif.'
        ]);

       // 2. Fitur Cerdas: Cari atau Buat Supplier Otomatis berdasarkan nama yang diketik
        $supplier = Supplier::firstOrCreate(
            ['name' => $request->supplier_name], // Data yang dicari
            [
                'phone'   => '-', // Nilai default otomatis jika agen baru dibuat
                'address' => '-'  // Nilai default otomatis jika agen baru dibuat
            ]
        );

        // Ambil daftar kolom asli dari tabel purchases di database Anda
        $columns = Schema::getColumnListing('purchases');

        // 3. Persiapkan data menggunakan ID dari supplier yang baru dicari/dibuat
        $data = [
            'supplier_id' => $supplier->id, 
            'user_id'     => auth()->id(),
        ];

        // Buat nomor nota otomatis jika kolom purchase_number ada
        if (in_array('purchase_number', $columns)) {
            $data['purchase_number'] = 'PO-' . date('YmdHis') . '-' . rand(100, 999);
        }
        if (in_array('purchase_date', $columns)) {
            $data['purchase_date'] = now();
        }
        if (in_array('date', $columns)) {
            $data['date'] = now();
        }
        if (in_array('product_id', $columns)) {
            $data['product_id'] = $request->product_id;
        }
        if (in_array('quantity', $columns)) {
            $data['quantity'] = $request->quantity;
        }
        if (in_array('total_cost', $columns)) {
            $data['total_cost'] = $request->total_cost;
        }
        if (in_array('total', $columns)) {
            $data['total'] = $request->total_cost;
        }
        if (in_array('total_amount', $columns)) {
            $data['total_amount'] = $request->total_cost;
        }
        if (in_array('status', $columns)) {
            $data['status'] = 'completed';
        }

        // 4. Simpan transaksi pembelian
        $purchase = Purchase::create($data);

        // Jika database Anda menggunakan tabel detail terpisah (purchase_items / purchase_details)
        $detailTable = Schema::hasTable('purchase_items') ? 'purchase_items' : (Schema::hasTable('purchase_details') ? 'purchase_details' : null);
        if ($detailTable) {
            $detailCols = Schema::getColumnListing($detailTable);
            $detailData = [
                'purchase_id' => $purchase->id,
                'product_id'  => $request->product_id,
                'quantity'    => $request->quantity,
            ];
            $unitPrice = $request->total_cost / max($request->quantity, 1);
            if (in_array('unit_cost', $detailCols)) $detailData['unit_cost'] = $unitPrice;
            if (in_array('price', $detailCols)) $detailData['price'] = $unitPrice;
            if (in_array('unit_price', $detailCols)) $detailData['unit_price'] = $unitPrice;
            if (in_array('purchase_price', $detailCols)) $detailData['purchase_price'] = $unitPrice;
            if (in_array('cost_price', $detailCols)) $detailData['cost_price'] = $unitPrice;
            if (in_array('subtotal', $detailCols)) $detailData['subtotal'] = $request->total_cost;
            if (in_array('total', $detailCols)) $detailData['total'] = $request->total_cost;
            if (in_array('created_at', $detailCols)) $detailData['created_at'] = now();
            if (in_array('updated_at', $detailCols)) $detailData['updated_at'] = now();

            DB::table($detailTable)->insert($detailData);
        }

        // 5. Tambah jumlah stok di tabel produk
        $product = Product::find($request->product_id);
        $product->increment('stock', $request->quantity);

        return redirect()->route('purchases.index')->with('success', 'Stok bahan baku berhasil ditambahkan!');
    }
}