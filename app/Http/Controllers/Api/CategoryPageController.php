<?php

namespace App\Http\Controllers\Api;


use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;

class CategoryPageController extends Controller
{
    public function show(Request $request)
    {

        try{
            $id = $request->input('id');
            $category = Categories::with('products')->findOrFail($id);
            return response([
                'message' => 'Category retrieved successfully',
                'data' => new CategoriesResource($category),
            ],200);


        }

        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }






    }
}
