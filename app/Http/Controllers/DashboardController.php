<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('pages.dashboard.dashboard-page');
    }

    function summary(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'User ID is not found required'], 400);
        }
        $products = Product::where('user_id', $user_id)->count();
        $categories = Category::where('user_id', $user_id)->count();
        $customers = Customer::where('user_id', $user_id)->count();
        $invoices = Invoice::where('user_id', $user_id)->count();
        $total = Invoice::where('user_id', $user_id)->sum('total');
        $salesToday = Invoice::where('user_id', $user_id)
            ->whereDate('created_at', now()->toDateString())
            ->sum('total');
        $salesThisMonth = Invoice::where('user_id', $user_id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $vat = Invoice::where('user_id', $user_id)->sum('vat');
        $payable = Invoice::where('user_id', $user_id)->sum('payable');

        return response()->json([
            'status' => 'success',
            'summary' => [
                'products' => $products,
                'categories' => $categories,
                'customers' => $customers,
                'invoices' => $invoices,
                'total' => round($total, 2),
                'sales_today' => round($salesToday, 2),
                'sales_this_month' => round($salesThisMonth, 2),
                'vat' => round($vat, 2),
                'payable' => round($payable, 2)
            ]
        ]);
    }



}
