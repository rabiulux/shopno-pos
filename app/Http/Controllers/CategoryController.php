<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.category-page');
    }

    public function getCategories(Request $request)
    {
        $user_id = $request->user_id;
        // $user_id = $request->header('user_id');
        if (!$user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $categories = Category::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'created_at']);

        return response()->json([
            'status' => 'success',
            'message' => 'Categories retrieved successfully',
            'categories' => $categories,
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            redirect()->route('login.page')->with('error', 'User ID is required');
            return response()->json(['error' => 'User ID is required'], 400);
        }
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category = Category::create([
            'name' => $request->name,
            'user_id' => $user_id,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }

    public function categoryById(Request $request)
    {
        $user_id = $request->user_id;
        $id = $request->id;
        if (!$user_id) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        try {
            $category = Category::where('user_id', $user_id)
                ->where('id', $id)->first(['id', 'name', 'created_at']);
            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Category retrieved successfully',
                'category' => $category,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user_id = $request->user_id;
        $category_id = $request->id;

        if (!$user_id || !$category_id) {
            return response()->json(['error' => 'Unauthorized'], 400);
        }

        $category = Category::where('user_id', $user_id)->where('id', $category_id)->first();

        if (!$category) {
            return response()->json(['error' => 'Unauthorized'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'category' => $category,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $id = $request->id;
            if (!$user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized action'
                ], 400);
            }

            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found'
                ], 404);
            }

            if ($category->user_id != $user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
