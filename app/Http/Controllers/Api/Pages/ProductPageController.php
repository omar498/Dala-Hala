<?php

namespace App\Http\Controllers\Api\Pages;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\ProductRateResource;

class ProductPageController extends Controller
{
    public function show(Request $request)
    {


        try{
            $id = $request->input('id');
            $product = Product::findOrFail($id);
            $averageRating = $product->averageRating();

            // Get common products from the same category
            $settings = Setting::all();
         $commonProducts = Product::where('category_id', $product->category_id)
         ->where('id' ,'!=', $product->id) //  prevent the current product from appearing
         ->take(3)
         ->get();


        return response([
            'Message'=>'product',
            'data'=>new ProductResource($product),
            'average_rating' =>$averageRating,
            'common_products' => ProductRateResource::collection($commonProducts),
            'footer'=>SettingResource::collection($settings),
        ],200);


    }

    catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Product not found'], 404);
    }




    }
}
