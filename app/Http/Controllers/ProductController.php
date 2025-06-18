<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.product-page');
    }

    // public function getProducts(Request $request)
    // {
    //     $user_id = $request->user_id;
    //     if (!$user_id) {
    //         return response()->json(['error' => 'Unauthorized'], 403);
    //     }
    //     // Assuming you have a Product model
    //     $products = Product::where('user_id', $user_id)
    //         ->with('category:id,name') // Eager load category
    //         ->orderBy('created_at', 'desc')
    //         ->get(['id', 'image', 'name', 'price', 'unit', 'quantity', 'created_at']);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Products retrieved successfully',
    //         'products' => $products,
    //     ], 200);
    // }

    public function getProducts(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $products = Product::where('user_id', $user_id)
            ->with([
                'category:id,name',
                'stock' => function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'image', 'name', 'price', 'unit', 'created_at']);

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'products' => $products,
        ], 200);
    }


    public function productById(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $product = Product::where('id', $request->id)
            ->where('user_id', $user_id)
            ->with('category:id,name') // Eager load category
            ->first();
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Product retrieved successfully',
            'product' => $product,
        ], 200);
    }

    public function store(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Validate and create the product
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'unit' => 'required|string|max:10',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $image = $request->file('image');
            if ($image) {
                $imageName = $user_id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
                $validatedData['image'] = 'images/products/' . $imageName;
            } else {
                $validatedData['image'] = null; // Handle case where no image is uploaded
            }

            $product = Product::create([
                'user_id' => $user_id,
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'unit' => $validatedData['unit'] ?? null,
                'quantity' => $validatedData['quantity'] ?? 0,
                'description' => $validatedData['description'] ?? null,
                'category_id' => $validatedData['category_id'],
                'image' => $validatedData['image'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'product' => $product,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function update(Request $request)
    {
        $user_id = $request->user_id;

        if (!$user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Validate the request
            $validatedData = $request->validate([
                'id' => 'required|exists:products,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'unit' => 'required|string|max:10',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $product = Product::where('id', $validatedData['id'])
                ->where('user_id', $user_id)
                ->first();

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Handle image upload
            $image = $request->file('image');
            if ($image) {
                // Upload new image
                $imageName = $user_id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
                $img_url = 'images/products/' . $imageName;

                $filePath = $request->input('file_path');
                File::delete($filePath);

                // Update product
                $product->update([
                    'name' => $request->name,
                    'price' => $request->price,
                    'description' => $request->description,
                    'unit' => $request->unit,
                    'quantity' => $request->quantity,
                    'category_id' => $request->category_id,
                    'image' => $img_url,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Product updated successfully',
                    'product' => $product,
                ], 200);
            } else {
                $product->update([
                    'name' => $request->name,
                    'price' => $request->price,
                    'description' => $request->description,
                    'unit' => $request->unit,
                    'quantity' => $request->quantity,
                    'category_id' => $request->category_id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Product updated successfully',
                    'product' => $product,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function destroy(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $product = Product::where('id', $request->id)
            ->where('user_id', $user_id)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Optional: Delete file if path is provided
        if ($request->has('file_path') && file_exists(public_path($request->file_path))) {
            unlink(public_path($request->file_path));
        }

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
