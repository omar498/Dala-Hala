<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Product::all();

        return response()->json(['message' => 'المنتجات',
        'categories' =>ProductResource::collection($categories)
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);
        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => new ProductResource($product),
            200
        ]);
    }

     public function delete_product(Request $request)
    {
        try{
        $id = $request->input('id');

        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully',
        'product'=>new ProductResource($product),

         200]);
        }

        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}

