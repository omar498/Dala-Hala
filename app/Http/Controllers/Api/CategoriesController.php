<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class CategoriesController extends Controller
{
    public function index()
    {
        // Fetch all categories
        $categories = Categories::all();

        // Return a JSON response
        return response()->json($categories, Response::HTTP_OK);
    }


    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name', // Ensure the name is unique
            'description' => 'nullable|string',
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
        // Validate that the ID is present in the request body
        $request->validate([
            'id' => 'required|integer|exists:categories,id', // Ensure the ID is valid and exists
        ],[
            'id.required' => 'The category ID is required to delete a category.',
            'id.integer' => 'The category ID must be an integer.',
            'id.exists' => 'The specified category ID does not exist.',
        ]
    );

        // Find the category by ID
        $category = Categories::find($request->input('id'));

        // Delete the category
        $category->delete();

        // Return a success response
        return response()->json([
            'message' => 'Category deleted successfully!'
        ], Response::HTTP_NO_CONTENT);
    }
}
