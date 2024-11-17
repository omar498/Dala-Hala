<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductRateResource;

class ProductPageController extends Controller
{
    public function show(Request $request)
    {
        try{
        $id = $request->input('id');
        $product = Product::findOrFail($id);
        $averageRating = $product->averageRating();

        return response([
            'Message'=>'product',
            'data'=>new ProductRateResource($product),
            'average_rating' =>$averageRating,
            200
        ]);
    }
    catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Product not found'], 404);
    }
    }
}
