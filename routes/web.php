<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// Ganti rute dashboard yang lama menjadi seperti ini:
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// --- Rute Penyelamat ---
Route::get('/fix-admin', function () { /* ... */ });
Route::get('/fix-kasir', function () { /* ... */ });
Route::get('/fix-customer', function () { /* ... */ });
Route::get('/fix-kategori', function () { /* ... */ });

// --- RUTE LOGIN & LOGOUT ---
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard'); 
    }
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// =======================================================
// AREA WAJIB LOGIN & PENGATURAN ROLE
// =======================================================
Route::middleware('auth')->group(function () {

    // --- 1. DASHBOARD MULTI-ROLE ---
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Uang Masuk dari Penjualan (dari tabel sales)
            $totalPenjualan = \App\Models\Sale::sum('total');
            
            // Uang Keluar (dari tabel purchases, kolomnya bernama 'total' sesuai database Anda)
            $totalPengeluaran = \App\Models\Purchase::sum('total');
            
            // Hitung Pendapatan Bersih
            $pendapatanBersih = $totalPenjualan - $totalPengeluaran;

            $totalProduk = \App\Models\Product::count();
            $stokMenipis = \App\Models\Product::where('stock', '<=', 5)->count();
            
            return view('dashboard', compact('totalPenjualan', 'totalProduk', 'stokMenipis', 'totalPengeluaran', 'pendapatanBersih'));
        }
        
        if ($user->role === 'staff') {
            // Menghitung SEMUA jumlah pesanan yang masuk ke warung hari ini
            $totalTransactions = \App\Models\Sale::whereDate('created_at', \Carbon\Carbon::today())->count();
            
            // Menghitung SEMUA total uang masuk ke kasir hari ini
            $totalRevenue = \App\Models\Sale::whereDate('created_at', \Carbon\Carbon::today())->sum('total');
            
            // Jika Anda sudah punya logika $popularProducts, tambahkan di sini. Jika belum, hapus saja koma dan variabelnya dari compact()
            return view('staff.dashboard', compact('totalTransactions', 'totalRevenue'));
        }

        // 2. Jika CUSTOMER
        $totalOrders = \App\Models\Sale::where('user_id', $user->id)->count();
        $popularProducts = \App\Models\Product::with('category')->inRandomOrder()->take(3)->get();
        
        return view('customer.dashboard', compact('totalOrders', 'popularProducts'));
        
    })->name('dashboard');

    // Rute cetak struk
    Route::get('/order/{id}/receipt', [SaleController::class, 'receipt'])->name('order.receipt');

    // ---------------------------------------------------
    // A. ROLE: STAFF & CUSTOMER (Sistem Kasir POS & Self-Service)
    // ---------------------------------------------------
    Route::middleware('role:staff,customer')->group(function () {
        Route::get('/menu', [ProductController::class, 'menuIndex'])->name('menu');
        Route::post('/order/confirm', [App\Http\Controllers\OrderController::class, 'confirm'])->name('order.confirm');
        Route::post('/order', [SaleController::class, 'store'])->name('order.store');
    });

    // ---------------------------------------------------
    // B. ROLE: CUSTOMER EKSKLUSIF
    // ---------------------------------------------------
    Route::middleware('role:customer')->group(function () {
        Route::get('/my-orders', [SaleController::class, 'myOrders'])->name('my.orders');
    });

    // ---------------------------------------------------
    // C. ROLE: STAFF & ADMIN
    // ---------------------------------------------------
    // Rute untuk Admin & Staff (termasuk Catat Stok Masuk)
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        
        Route::get('/orders', [SaleController::class, 'index'])->name('orders.index');
        Route::match(['post', 'patch'], '/orders/{id}/complete', [SaleController::class, 'complete'])->name('orders.complete');
    });

    // ---------------------------------------------------
    // D. ROLE: ADMIN / OWNER
    // ---------------------------------------------------
    Route::middleware('role:admin')->group(function () {
        // Kelola Pengguna
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Master Data Lainnya
        Route::get('/laporan-transaksi', [\App\Http\Controllers\SaleController::class, 'laporan'])->name('admin.transactions');
        Route::post('/orders/{id}/cancel', [SaleController::class, 'cancel'])->name('orders.cancel');
        
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::resource('suppliers', SupplierController::class);
        Route::resource('categories', CategoryController::class);
    });

});