<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class SubscriptionController extends Controller
{
    public function index()
    {
        // Fetch available subscription plans from the database
        $plans = Plan::where('active', true)->get();
        $months = plan::where('interval', 'monthly')->where('active', true)->get();
        $years = plan::where('interval', 'yearly')->where('active', true)->get();

        return view('frontend.pages.subscriptions.index', compact('plans', 'months', 'years'));
    }
}
