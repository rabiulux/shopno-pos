<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchasePage()
    {
        // Logic to display the purchase page
        return view('pages.dashboard.purchase-page');
    }
    public function purchaseCreatePage()
    {
        // Logic to display the purchase page
        return view('pages.dashboard.purchase-create-page');
    }

    public function purchaseCreate(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'User ID is not found required'], 400);
        }
        $validatedData = $request->validate([
            'supplier_id' => 'required|integer',
            'total' => 'required|numeric',
            'discount' => 'required|numeric',
            'vat' => 'required|numeric',
            'payable' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'user_id' => $user_id,
                'supplier_id' => $validatedData['supplier_id'],
                'total' => $validatedData['total'],
                'discount' => $validatedData['discount'],
                'vat' => $validatedData['vat'],
                'payable' => $validatedData['payable'],
            ]);

            $purchaseId = $purchase->id;
            $products = $request->input('products', []);
            // foreach ($products as $product) {
            //     PurchaseProduct::create([
            //         'user_id' => $user_id,
            //         'purchase_id' => $purchaseId,
            //         'product_id' => $product['product_id'],
            //         'quantity' => $product['quantity'],
            //         'purchase_price' => $product['purchase_price'],
            //     ]);
            // }

            foreach ($products as $product) {
                $productId = $product['product_id'];
                $quantity = $product['quantity'];

                // 1. Save purchase product
                PurchaseProduct::create([
                    'user_id' => $user_id,
                    'purchase_id' => $purchaseId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'purchase_price' => $product['purchase_price'],
                ]);

                // 2. Update stock (add quantity)
                $productStock = ProductStock::firstOrNew([
                    'user_id' => $user_id,
                    'product_id' => $productId,
                ]);

                $productStock->quantity = ($productStock->quantity ?? 0) + $quantity;
                $productStock->save();
            }


            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Purchase created successfully',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "failed", 'message' => 'Purchase creation failed: ' . $e->getMessage()], 500);
        }
    }

    public function getPurchases(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
        }
        $purchases = Purchase::with(['supplier'])
            ->where('user_id', $user_id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Purchases retrieved successfully',
            'purchases' => $purchases,
        ], 200);
    }

    public function purchaseDetails(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
        }
        try {
            $request->validate([
                'purchase_id' => 'required',
                'supplier_id' => 'required'
            ]);

            $purchase_id = $request->purchase_id;
            $supplier_id = $request->supplier_id;

            $supplierDetails = Supplier::where('id', $supplier_id)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $purchaseTotal = Purchase::where('id', $purchase_id)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $purchaseProducts = PurchaseProduct::with(['products'])->where('purchase_id', $purchase_id)
                ->where('user_id', $user_id)
                ->get();


            return response()->json([
                'status' => 'success',
                'supplier' => $supplierDetails,
                'purchase' => $purchaseTotal,
                'products' => $purchaseProducts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "error",
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    public function deletePurchase(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['status' => "failed", 'message' => 'User ID is required'], 400);
        }

        $purchase_id = $request->input('id');
        if (!$purchase_id) {
            return response()->json(['status' => "failed", 'message' => 'Purhcase ID is required'], 400);
        }
        DB::beginTransaction();
        try {
            // Delete purchase products
            PurchaseProduct::where('purchase_id', $purchase_id)
                ->where('user_id', $user_id)->delete();

            // Delete the purchase
            Purchase::where('id', $purchase_id)
                ->where('user_id', $user_id)->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Purchase deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "failed", 'message' => 'Failed to delete Purchase: ' . $e->getMessage()], 500);
        }
    }
}
