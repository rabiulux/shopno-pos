<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function InvoicePage()
    {
        return view('pages.dashboard.invoice-page');
    }
    public function SalesPage()
    {
        return view('pages.dashboard.sale-page');
    }

    public function invoiceCreate(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'User ID is not found required'], 400);
        }
        $validatedData = $request->validate([
            'customer_id' => 'required|integer',
            'total' => 'required|numeric',
            'discount' => 'required|numeric',
            'vat' => 'required|numeric',
            'payable' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {

            $invoice = Invoice::create([
                'user_id' => $user_id,
                'customer_id' => $validatedData['customer_id'],
                'total' => $validatedData['total'],
                'discount' => $validatedData['discount'],
                'vat' => $validatedData['vat'],
                'payable' => $validatedData['payable'],
            ]);

            $invoiceId = $invoice->id;
            $products = $request->input('products', []);
            // foreach ($products as $product) {
            //     InvoiceProduct::create([
            //         'user_id' => $user_id,
            //         'invoice_id' => $invoiceId,
            //         'product_id' => $product['product_id'],
            //         'quantity' => $product['quantity'],
            //         'sale_price' => $product['sale_price'],
            //     ]);
            // }

            foreach ($products as $product) {
                $productId = $product['product_id'];
                $quantity = $product['quantity'];

                // 1. Save invoice product
                InvoiceProduct::create([
                    'user_id' => $user_id,
                    'invoice_id' => $invoiceId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'sale_price' => $product['sale_price'],
                ]);

                // 2. Reduce stock quantity
                $productStock = ProductStock::where('user_id', $user_id)
                    ->where('product_id', $productId)
                    ->first();

                if ($productStock) {
                    $productStock->quantity -= $quantity;

                    // Prevent negative stock
                    if ($productStock->quantity < 0) {
                        $productStock->quantity = 0;
                    }

                    $productStock->save();
                } else {
                    // Optional: Log a warning or handle case where no stock entry exists
                }
            }


            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice created successfully',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "failed", 'message' => 'Failed to create invoice: ' . $e->getMessage()], 500);
        }
    }

    public function invoiceSelect(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
            }
            $invoices = Invoice::with(['customer'])
                ->where('user_id', $user_id)->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Invoices retrieved successfully',
                'invoices' => $invoices,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => "failed", 'message' => 'Failed to create invoice: ' . $e->getMessage()], 500);
        }
    }

    public function InvoiceDetails(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
        }
        try {
            $request->validate([
                'invoice_id' => 'required',
                'customer_id' => 'required'
            ]);

            $invoiceId = $request->invoice_id;
            $customerId = $request->customer_id;

            $customerDetails = Customer::where('id', $customerId)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $invoiceTotal = Invoice::where('id', $invoiceId)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $invoiceProducts = InvoiceProduct::with(['products'])->where('invoice_id', $invoiceId)
                ->where('user_id', $user_id)
                ->get();



            return response()->json([
                'status' => 'success',
                'customer' => $customerDetails,
                'invoice' => $invoiceTotal,
                'products' => $invoiceProducts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "error",
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    public function deleteInvoice(Request $request)
    {

        DB::beginTransaction();
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['status' => "failed", 'message' => 'User ID is required'], 400);
            }

            $invoiceId = $request->input('id');
            if (!$invoiceId) {
                return response()->json(['status' => "failed", 'message' => 'Invoice ID is required'], 400);
            }

            // Delete invoice products
            InvoiceProduct::where('invoice_id', $invoiceId)
                ->where('user_id', $user_id)->delete();

            // Delete the invoice
            Invoice::where('id', $invoiceId)
                ->where('user_id', $user_id)->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "failed", 'message' => 'Failed to delete invoice: ' . $e->getMessage()], 500);
        }
    }
}
