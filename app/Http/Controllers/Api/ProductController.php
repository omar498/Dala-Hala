<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();

        return response()->json([
            'message' => 'المنتجات',
        'data' =>ProductResource::collection($product)
        ], 200);
    }

    public function store(ProductStoreRequest $request)
    {

        $product = Product::create($request->validated());

        return response()->json([
            'message' => 'Product added successfully!',
            'data' => new ProductResource($product),
            200
        ]);
    }

     public function delete_product(Request $request)
    {
        try{
        $id = $request->input('id');

        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
        'message' => 'Product deleted successfully',
        'data'=>new ProductResource($product),

         200]);
        }

        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}

