<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.customer-page');
    }

    /**
     * Get all customers.
     */
    public function getCustomers(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            // Assuming you have a Customer model
            $customers = Customer::where('user_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'name', 'email', 'mobile', 'created_at']);

            return response()->json([
                'status' => 'success',
                'message' => 'Customers retrieved successfully',
                'customers' => $customers,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while retrieving the customer'], 500);
        }
    }

    public function customerById(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $customer = Customer::where('id', $request->id)
                ->where('user_id', $user_id)
                ->first(['id', 'name', 'email', 'mobile']);
            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Customer retrieved successfully',
                'customer' => $customer,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'An error occurred while retrieving the customers',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|email|max:50|unique:customers,email,NULL,id,user_id,' . $user_id,
                'mobile' => 'required|string|max:15',
            ]);
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $user_id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
                'customer' => $customer,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'An error occurred while storing the customer',
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $request->validate([
                'id' => 'required|exists:customers,id,user_id,' . $user_id,
                'name' => 'required|string|max:50',
                'email' => 'required|email|max:50|unique:customers,email,' . $request->id . ',id,user_id,' . $user_id,
                'mobile' => 'required|string|max:15',
            ]);
            $customer = Customer::where('id', $request->id)
                ->where('user_id', $user_id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Customer updated successfully',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'An error occurred while updating the customer',
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if (!$user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $customer = Customer::where('id', $request->id)
                ->where('user_id', $user_id)
                ->first();
            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }
            $customer->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Customer deleted successfully',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while deleting the customer'], 500);
        }
    }
}
