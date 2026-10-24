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
}