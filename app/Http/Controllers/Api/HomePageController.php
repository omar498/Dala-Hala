<?php

namespace App\Http\Controllers\Api;

use App\Models\Images;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\ProductHomeResource;

class HomePageController extends Controller
{
    public function index()
    {
    $categories = Categories::all();


    $mostSellingProducts = Product::withCount('carts')
    ->orderBy('carts_count', 'desc')
    ->take(3)
    ->get();

    $categories_with_products = Categories::with(['products' => function($query) {
        $query->take(3);
    }])->get();



    $settings = Setting::all();

    // Call Certain Item

    $header_image = Images::where('image_title', 'home page header')->first();
    $body_image = Images::where('image_title', 'home page body')->first();
    $footer_image = Images::where('image_title', 'home page footer')->first();



    return response()->json([
        'message' => 'الاقسام والمنتجات',

       'header_image'=> ImageResource::make($header_image),

        'categories' => CategoriesResource::collection($categories),

        'most_selling_products' => ProductHomeResource::collection($mostSellingProducts),

        'body_image' => ImageResource::make($body_image),

        'products' => CategoriesResource::collection($categories_with_products),

        'footer_image' => ImageResource::make($footer_image),

        'footer' => SettingResource::collection($settings)
    ], 200,);
    }
}
