<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function supplierPage()
    {
        return view('pages.dashboard.supplier-page');
    }

    public function getSuppliers(Request $request)
    {

        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
            }
            // Fetch suppliers logic here
            $suppliers = Supplier::where('user_id', $user_id)->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Customers retrieved successfully',
                'suppliers' => $suppliers,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while retrieving the suppliers'], 500);
        }
    }

    public function supplierById(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
            }
            $supplier_id = $request->id;
            if (!$supplier_id) {
                return response()->json(['status' => "failed", 'message' => 'Supplier ID is not found required'], 400);
            }
            $supplier = Supplier::find($supplier_id);
            if (!$supplier) {
                return response()->json(['status' => "failed", 'message' => 'Supplier not found'], 404);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier retrieved successfully',
                'supplier' => $supplier,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while retrieving the supplier'], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
            }
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'mobile' => 'nullable|string|max:15',
                'user_id' => 'required|exists:users,id',
            ]);

            $supplier = Supplier::create($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier created successfully',
                'supplier' => $supplier,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while creating the supplier'], 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
            }
            $request->validate([
                'id' => 'required|exists:suppliers,id,user_id,' . $user_id,
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'mobile' => 'required|string|max:15',
            ]);


            $supplier = Supplier::where('id', $request->id)
                ->where('user_id', $user_id)
                ->first();
            if (!$supplier) {
                return response()->json(['status' => "failed", 'message' => 'Supplier not found'], 404);
            }
            $supplier->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier updated successfully',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while updating the supplier'], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['status' => "failed", 'message' => 'Unauthorized'], 403);
            }
            $supplier_id = $request->supplier_id;
            if (!$supplier_id) {
                return response()->json(['status' => "failed", 'message' => 'Supplier ID is not found required'], 400);
            }
            $supplier = Supplier::find($supplier_id);
            if (!$supplier) {
                return response()->json(['status' => "failed", 'message' => 'Supplier not found'], 404);
            }
            $supplier->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier deleted successfully',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while deleting the supplier'], 500);
        }
    }
}
