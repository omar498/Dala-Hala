<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

     public function create()
     {
         $categories = Categories::all();
         return response()->json($categories);
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
        return response()->json($product, 201);
    }


     public function destroy($id)
     {
         $product = Product::findOrFail($id);
         $product->delete();
         return response()->json(null, 204);
     }
}

