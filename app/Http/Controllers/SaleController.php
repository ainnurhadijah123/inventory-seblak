<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;

class SaleController extends Controller
{
    // 1. Menampilkan daftar pesanan masuk (Untuk Staff)
    public function index(Request $request)
    {
        $query = \App\Models\Sale::latest();

        // Logika Filter berdasarkan Tanggal Pesanan
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Gunakan pagination agar rapi jika data sudah banyak
        $orders = $query->paginate(15); 

        // Jika yang login adalah Admin, arahkan ke view khusus Admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return view('admin.orders', compact('orders'));
        }

        // Jika Staff, tetap gunakan view yang lama
        return view('orders.index', compact('orders'));
    }

    // 2. Mengubah status pesanan menjadi selesai (Untuk Staff)
    public function complete($id)
    {
        $order = Sale::findOrFail($id);
        
        if (Schema::hasColumn('sales', 'status')) {
            $order->status = 'completed';
            $order->save();
        }

        return redirect()->back()->with('success', 'Pesanan ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . ' berhasil diselesaikan!');
    }

    public function store(Request $request)
    {
        $items = array_filter($request->input('items', []), fn($qty) => $qty > 0);

        if (empty($items)) {
            return back()->with('error', 'Pilih minimal 1 menu seblak atau minuman terlebih dahulu!');
        }

        return DB::transaction(function () use ($request, $items) {
            $spicyPrices = [0 => 0, 1 => 0, 2 => 1000, 3 => 2000, 4 => 3000, 5 => 5000];
            $spicyLevel = (int) $request->input('spicy_level', 0);
            $spicyCost  = $spicyPrices[$spicyLevel] ?? 0;
            $totalBelanja = 0;
            $detailItems  = [];

            foreach ($items as $productId => $qty) {
                $product = \App\Models\Product::lockForUpdate()->findOrFail($productId);
                
                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'error' => "Stok {$product->name} tidak mencukupi! Sisa stok: {$product->stock}"
                    ]);
                }

                $hargaSatuan = $product->price ?? $product->selling_price ?? 0;
                $subtotal = $hargaSatuan * $qty; // TYPO DIPERBAIKI DI SINI
                $totalBelanja += $subtotal;

                $detailItems[] = [
                    'product'  => $product,
                    'qty'      => $qty,
                    'price'    => $hargaSatuan,
                    'subtotal' => $subtotal,
                ];
            }

            $grandTotal = $totalBelanja + $spicyCost;
            $columns = Schema::getColumnListing('sales');
            $saleData = ['user_id' => auth()->id()];

            if (in_array('invoice_number', $columns)) $saleData['invoice_number'] = 'INV-' . date('YmdHis') . '-' . rand(100, 999);
            if (in_array('sale_number', $columns))    $saleData['sale_number']    = 'INV-' . date('YmdHis') . '-' . rand(100, 999);
            if (in_array('customer_name', $columns))  $saleData['customer_name']  = $request->customer_name ?? auth()->user()->name;
            if (in_array('spicy_level', $columns))    $saleData['spicy_level']    = $spicyLevel;
            if (in_array('level', $columns))          $saleData['level']          = $spicyLevel;
            if (in_array('spicy_cost', $columns))     $saleData['spicy_cost']     = $spicyCost;
            if (in_array('notes', $columns))          $saleData['notes']          = "Level Pedas: {$spicyLevel} (+Rp " . number_format($spicyCost, 0, ',', '.') . ")";
            if (in_array('total', $columns))          $saleData['total']          = $grandTotal;
            if (in_array('total_amount', $columns))   $saleData['total_amount']   = $grandTotal;
            if (in_array('total_price', $columns))    $saleData['total_price']    = $grandTotal;
            if (in_array('status', $columns))         $saleData['status']         = 'pending';
            if (in_array('sale_date', $columns))      $saleData['sale_date']      = now();

            $sale = Sale::create($saleData);
            $detailTable = Schema::hasTable('sale_items') ? 'sale_items' : (Schema::hasTable('sale_details') ? 'sale_details' : null);

            foreach ($detailItems as $item) {
                if ($detailTable) {
                    $detailCols = Schema::getColumnListing($detailTable);
                    $row = [
                        'sale_id'    => $sale->id,
                        'product_id' => $item['product']->id,
                        'quantity'   => $item['qty'],
                    ];
                    if (in_array('price', $detailCols))      $row['price']      = $item['price'];
                    if (in_array('unit_price', $detailCols)) $row['unit_price'] = $item['price'];
                    if (in_array('subtotal', $detailCols))   $row['subtotal']   = $item['subtotal'];
                    if (in_array('created_at', $detailCols)) $row['created_at'] = now();
                    if (in_array('updated_at', $detailCols)) $row['updated_at'] = now();

                    DB::table($detailTable)->insert($row);
                }
                $item['product']->decrement('stock', $item['qty']);
            }

            return redirect()->route('order.receipt', $sale->id)->with('success', 'Pesanan berhasil dibuat!');
        });
    }

    // 4. Menampilkan halaman struk
    public function receipt($id)
    {
        $order = Sale::with('items.product')->findOrFail($id);
        
        if (auth()->user()->role === 'customer' && $order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke struk ini.');
        }

        return view('orders.receipt', compact('order'));
    }

    // 5. Menampilkan riwayat pesanan khusus untuk Customer
    public function myOrders()
    {
        $orders = Sale::where('user_id', auth()->id())->latest()->get();
        return view('orders.customer_history', compact('orders'));
    }

    // 6. Menampilkan Laporan Transaksi (Untuk Admin) beserta Grafik
    public function laporan()
    {
        // PERBAIKAN: Tambahkan with('user') agar nama pelanggan bisa ditarik
        $orders = Sale::with('user')->latest()->paginate(10);
        $totalRevenue = Sale::where('status', 'completed')->sum('total');

        $chartData = Sale::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->limit(7)
            ->get();

        $chartLabels = $chartData->pluck('date');
        $chartValues = $chartData->pluck('revenue');

        return view('admin.transactions', compact('orders', 'totalRevenue', 'chartLabels', 'chartValues'));
    }
}