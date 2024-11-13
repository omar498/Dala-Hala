<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\SettingResource;
use App\Models\Setting;

class HomePageController extends Controller
{
    public function index()
    {
    $categories = Categories::all();
   // $categories_with_products = Categories::with('products')->get();
    $categories_with_products = Categories::with(['products' => function($query) {
        $query->take(3);
    }])->get();
    $settings = Setting::all();


    return response()->json([
        'message' => 'الاقسام والمنتجات',
        'categories' => CategoriesResource::collection($categories),
        'products' => CategoriesResource::collection($categories_with_products),
        'Settings' => SettingResource::collection($settings)
    ], 200);
    }

}
