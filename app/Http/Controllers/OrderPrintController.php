<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderPrintController extends Controller
{
    public function printInvoice(Order $order)
    {
        return view('orders.print-invoice', compact('order'));
    }

    public function printShippingLabel(Order $order)
    {
        return view('orders.print-shipping-label', compact('order'));
    }
} 