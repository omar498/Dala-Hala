<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoriesResource;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::all();

        return response()->json(['message' => 'الاقسام',
        'categories' =>CategoriesResource::collection($categories)
        ], 200);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ],[
            'name.unique' => 'The category name must be unique. Please choose another name.',
        ]);
        $category = Categories::create($validated);
        return response()->json([
            'message' => 'Category created successfully!',
            'category' => $category
        ], Response::HTTP_CREATED);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:categories,id',
        ],[
            'id.required' => 'The category ID is required to delete a category.',
            'id.integer' => 'The category ID must be an integer.',
            'id.exists' => 'The specified category ID does not exist.',

        ]);
        // Find the category by ID
        $category = Categories::find($request->input('id'));
        
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category. It contains products.',
            ], 400); // Return a 400 Bad Request status
        }
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully!',
            'category'=>new CategoriesResource($category),
            200
        ]);
    }
}
