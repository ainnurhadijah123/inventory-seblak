<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Demo (Admin, Staff, Customer)
        User::create([
            'name' => 'Admin Pemilik',
            'email' => 'admin@seblak.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Staf Kasir',
            'email' => 'staff@seblak.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'customer@seblak.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        // 2. Buat Kategori
        $kategoriId = DB::table('categories')->insertGetId([
            'name' => 'Topping Utama',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Buat Pemasok (Supplier)
        $supplierId = DB::table('suppliers')->insertGetId([
            'name' => 'Agen Sosis Krueng Geukuh',
            'phone' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Buat Produk Demo
        DB::table('products')->insert([
            'category_id' => $kategoriId,
            'sku' => 'TOP-001',
            'name' => 'Sosis Sapi',
            'purchase_price' => 1500,
            'selling_price' => 3000,
            'stock' => 50,
            'minimum_stock' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}