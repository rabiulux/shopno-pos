<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\Models\PurchaseProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function ReportPage()
    {
        return view('pages.dashboard.report-page');
    }

    function salesReport(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'User ID is not found required'], 400);
        }

        $FromDate = date('Y-m-d', strtotime($request->FromDate));
        if (!$FromDate) {
            return response()->json(['status' => "failed", 'message' => 'From Date is required'], 400);
        }
        $ToDate = date('Y-m-d', strtotime($request->ToDate));
        if (!$ToDate) {
            return response()->json(['status' => "failed", 'message' => 'To Date is required'], 400);
        }
        if ($FromDate > $ToDate) {
            return response()->json(['status' => "failed", 'message' => 'From Date must be less than To Date'], 400);
        }


        $total = Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('total');
        $vat = Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('vat');
        $payable = Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('payable');
        $discount = Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('discount');

        $invoices = Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->with('customer')->get();

        $data = [
            'total' => $total,
            'vat' => $vat,
            'payable' => $payable,
            'discount' => $discount,
            'invoices' => $invoices,
            'status' => 'success',
            'message' => 'Sales report generated successfully.'
        ];

        if ($invoices->isEmpty()) {
            return redirect()->back()->with('error', 'No sales report found. Please select a different date range.');
        }

        $pdf = Pdf::loadView('reports.sales-report-pdf', $data);
        return $pdf->download('sales_report.pdf');
    }

    public function purchaseReport(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'User ID is not found required'], 400);
        }

        $FromDate = date('Y-m-d', strtotime($request->FromDate));
        if (!$FromDate) {
            return response()->json(['status' => "failed", 'message' => 'From Date is required'], 400);
        }
        $ToDate = date('Y-m-d', strtotime($request->ToDate));
        if (!$ToDate) {
            return response()->json(['status' => "failed", 'message' => 'To Date is required'], 400);
        }
        if ($FromDate > $ToDate) {
            return response()->json(['status' => "failed", 'message' => 'From Date must be less than To Date'], 400);
        }
        $total = Purchase::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('total');

        $vat = Purchase::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('vat');
        $payable = Purchase::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('payable');
        $discount = Purchase::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('discount');

        $purchases = Purchase::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->with('supplier')->get();

        $data = [
            'total' => $total,
            'vat' => $vat,
            'payable' => $payable,
            'discount' => $discount,
            'purchases' => $purchases,
            'status' => 'success',
            'message' => 'Purchase report generated successfully.'
        ];

        if ($purchases->isEmpty()) {
            return redirect()->back()->with('error', 'No purchase report found. Please select a different date range.');
        }

        $pdf = Pdf::loadView('reports.purchases-report-pdf', $data);
        return $pdf->download('purchase_report.pdf');
    }
    /*
    public function profitReport(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'User ID is not found required'], 400);
        }

        $FromDate = date('Y-m-d', strtotime($request->FromDate));
        if (!$FromDate) {
            return response()->json(['status' => "failed", 'message' => 'From Date is required'], 400);
        }
        $ToDate = date('Y-m-d', strtotime($request->ToDate));
        if (!$ToDate) {
            return response()->json(['status' => "failed", 'message' => 'To Date is required'], 400);
        }
        if ($FromDate > $ToDate) {
            return response()->json(['status' => "failed", 'message' => 'From Date must be less than To Date'], 400);
        }

        // Fetch sales total (payable)
        $totalSales = Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('payable');

        // Fetch purchase total (payable)
        $totalPurchases = Purchase::where('user_id', $user_id)
            ->whereBetween('created_at', [$FromDate, $ToDate])
            ->sum('payable');

        $profitOrLoss = $totalSales - $totalPurchases;

        $data = [
            'totalSales' => $totalSales,
            'totalPurchases' => $totalPurchases,
            'profitOrLoss' => $profitOrLoss,
            'status' => 'success',
            'message' => 'Profit and loss report generated successfully.'
        ];

        $pdf = Pdf::loadView('reports.profit-loss-report-pdf', $data);
        return $pdf->download('profit_loss_report.pdf');
    }
*/
    /*
    public function profitReport(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'User ID is required'], 400);
        }

        $FromDate = date('Y-m-d', strtotime($request->FromDate));
        $ToDate = date('Y-m-d', strtotime($request->ToDate));

        if (!$FromDate || !$ToDate) {
            return response()->json(['status' => "failed", 'message' => 'Both From Date and To Date are required'], 400);
        }

        if ($FromDate > $ToDate) {
            return response()->json(['status' => "failed", 'message' => 'From Date must be before To Date'], 400);
        }

        // Get sold products within date range
        $soldItems = InvoiceProduct::with(['product'])
            ->whereHas('invoice', function ($query) use ($user_id, $FromDate, $ToDate) {
                $query->where('user_id', $user_id)
                    ->whereBetween('created_at', [$FromDate, $ToDate]);
            })
            ->get();

        $totalRevenue = 0;
        $totalCOGS = 0;

        foreach ($soldItems as $item) {
            $totalRevenue += $item->sales_price * $item->quantity;

            // Get average purchase price for the product
            $avgPurchasePrice = \App\Models\PurchaseProduct::where('product_id', $item->product_id)
                ->whereHas('purchase', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })
                ->avg('purchase_price') ?? 0;

            $totalCOGS += $avgPurchasePrice * $item->quantity;
        }

        $profitOrLoss = $totalRevenue - $totalCOGS;

        $data = [
            'fromDate' => $FromDate,
            'toDate' => $ToDate,
            'totalRevenue' => $totalRevenue,
            'totalCOGS' => $totalCOGS,
            'profitOrLoss' => $profitOrLoss,
            'status' => 'success',
            'message' => 'Profit and loss report generated successfully.'
        ];

        $pdf = Pdf::loadView('reports.profit-loss-report-pdf', $data);
        return $pdf->download('profit_loss_report.pdf');
    }
        */

    public function profitReport(Request $request)
    {
        try {
            // Validate required inputs
            // $validator = Validator::make($request->all(), [
            //     'user_id' => 'required|integer|exists:users,id',
            //     'FromDate' => 'required|date',
            //     'ToDate' => 'required|date|after_or_equal:FromDate',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => "failed",
            //         'message' => 'Validation failed',
            //         'errors' => $validator->errors()
            //     ], 400);
            // }

            $user_id = $request->user_id;
            $FromDate = date('Y-m-d 00:00:00', strtotime($request->FromDate));
            $ToDate = date('Y-m-d 23:59:59', strtotime($request->ToDate));

            // Get sold products within date range
            $soldItems = InvoiceProduct::with(['product'])
                ->whereHas('invoice', function ($query) use ($user_id, $FromDate, $ToDate) {
                    $query->where('user_id', $user_id)
                        ->whereBetween('created_at', [$FromDate, $ToDate]);
                })
                ->get();

            $totalRevenue = 0;
            $totalCOGS = 0;
            $missingCostProducts = [];

            foreach ($soldItems as $item) {
                // Calculate revenue (fixed field name from sales_price to sale_price)
                $totalRevenue += $item->sale_price * $item->quantity;

                // Calculate COGS using average purchase price
                $purchaseProducts = PurchaseProduct::where('product_id', $item->product_id)
                    ->whereHas('purchase', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })
                    ->get();

                if ($purchaseProducts->isEmpty()) {
                    $missingCostProducts[] = [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? 'Unknown',
                        'quantity' => $item->quantity
                    ];
                    continue;
                }

                $avgPurchasePrice = $purchaseProducts->avg('purchase_price');
                $totalCOGS += $avgPurchasePrice * $item->quantity;
            }

            $profitOrLoss = $totalRevenue - $totalCOGS;

            $data = [
                'fromDate' => $request->FromDate,
                'toDate' => $request->ToDate,
                'totalRevenue' => round($totalRevenue, 2),
                'totalCOGS' => round($totalCOGS, 2),
                'profitOrLoss' => round($profitOrLoss, 2),
                'missingCostProducts' => $missingCostProducts,
                'status' => 'success',
                'message' => 'Profit and loss report generated successfully.'
            ];

            // Generate PDF report
            $pdf = Pdf::loadView('reports.profit-loss-report-pdf', $data);
            return $pdf->download('profit_loss_report_' . date('Ymd_His') . '.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'status' => "failed",
                'message' => 'Error generating profit report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
