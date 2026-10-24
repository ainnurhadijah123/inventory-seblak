<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');

        $query = Sale::with('user')->latest();

        if ($filter === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($filter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        // Variabel ini yang wajib dikirim ke view dashboard
        $recentTransactions = $query->take(5)->get();

        $totalPenjualan = Sale::sum('total') ?: Sale::sum('total_price') ?: Sale::sum('total_amount') ?: 0;
        $totalProduk = Product::count();
        $stokMenipis = Product::where('stock', '<=', 5)->count();

        return view('dashboard', compact('recentTransactions', 'totalPenjualan', 'totalProduk', 'stokMenipis', 'filter'));
    }
}