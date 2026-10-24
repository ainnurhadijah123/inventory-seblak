<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class); // Kasir atau Customer
    }
    public function items() {
        return $this->hasMany(SaleItem::class);
    }
}