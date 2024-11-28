<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Intervention\Image\Laravel\Facades\Image;

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
            $resizedMainImagePath = 'category/' . time() . '_' . $request->file('image')->getClientOriginalName();
            $resizedMainImage = Image::read($request->file('image'))->resize(200, 300);
            $resizedMainImage->save(storage_path('app/public/images/' . $resizedMainImagePath));
        }
        $categorytData ['image_path'] = $resizedMainImagePath;
        $category = Categories::create($categorytData);

        return response()->json([
            'message' => 'Category created successfully!',
            'data' => new CategoriesResource($category)
        ], Response::HTTP_CREATED);
    }

    public function update(CategoryUpdateRequest $request)
    {
        // Get the category ID from the request body
        $id = $request->input('id');

        // Retrieve the existing category
        $category = Categories::findOrFail($id);

        // Prepare validated data
        $categoryData = $request->validated();

        // Check if a new image file is uploaded
        if ($request->hasFile('image')) {
            // Generate a new path for the resized image
            $resizedMainImagePath = 'category/' . time() . '_' . $request->file('image')->getClientOriginalName();
            $resizedMainImage = Image::read($request->file('image'))->resize(200, 300);
            $resizedMainImage->save(storage_path('app/public/images/' . $resizedMainImagePath));

            // Update the image path in validated data
            $categoryData['image_path'] = $resizedMainImagePath;
        }

        // Update the existing category record
        $category->update($categoryData);

        // Return a success response with the updated category
        return response()->json([
            'message' => 'Category updated successfully!',
            'data' => new CategoriesResource($category)
        ], Response::HTTP_OK);
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
        $category = Categories::find($request->input('id'));

        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category. It contains products.',
            ], 400); // Bad request
        }
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully!',
            'data'=>new CategoriesResource($category),
            200
        ]);
    }
}
