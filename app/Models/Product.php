<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Mengizinkan semua kolom (termasuk sku) untuk diisi otomatis
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}