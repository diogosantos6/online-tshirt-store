<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use Illuminate\View\View;

class OrderItemController extends Controller
{
    public function index(): View
    {
        $orderItems = OrderItem::all();
        return view('order_items.index', compact('orderItems'));
    }
}
