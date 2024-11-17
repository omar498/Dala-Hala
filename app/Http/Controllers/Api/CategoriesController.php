<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Http\Requests\CategoryStoreRequest;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::all();

        return response()->json(['message' => 'الاقسام',
        'data' =>CategoriesResource::collection($categories)
        ], 200);
    }



    public function store(CategoryStoreRequest $request)
    {
        $categorytData = $request->validated();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category', 'images');
            $categorytData['image_path'] = $imagePath;
        }
        $category = Categories::create($categorytData);

        return response()->json([
            'message' => 'Category created successfully!',
            'data' => new CategoriesResource($category)
        ], Response::HTTP_CREATED);
    }

    public function delete(Request $request)
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
            'data'=>new CategoriesResource($category),
            200
        ]);
    }
}
