<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ConsumerController;
use App\Http\Controllers\Api\HomePageController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\CategoryPageController;
use App\Http\Controllers\Api\ProductPageController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('homepage', [HomePageController::class, 'index']);


Route::group(['prefix' => 'auth'], function ($router)
{
    Route::post('login', [AuthController::class ,'login']);
    Route::post('register', [AuthController::class ,'register']);

});
// Consumer Auth
Route::group(['prefix' => 'user'], function ($router){
    Route::post('login', [ConsumerController::class ,'login']);
    Route::post('register', [ConsumerController::class ,'register']);
});

Route::middleware(['auth:consumer-api'])->group(function(){
    Route::post('refresh_user_token', [ConsumerController::class, 'refreshToken']);
    Route::post('user_logout', [ConsumerController::class ,'logout']);

    Route::Post('createCart', [CartController::class, 'makeCart']);
    Route::Post('AddToCart', [CartController::class, 'addToCart']);
    Route::Post('showCart', [CartController::class, 'showCart']);

    Route::post('rates', [RateController::class, 'store']);

    Route::get('product_page', [ProductPageController::class, 'show']);
    Route::get('catrgory_page', [CategoryPageController::class, 'show']);

});



Route::middleware(['auth:api'])->group(function(){
    // Admin Function
    Route::post('refresh_token', [AuthController::class, 'refreshToken']);
    Route::post('logout', [AuthController::class ,'logout']);
    Route::delete('remove', [AuthController::class,'delete_admin']);
    // Manage Users Functions
    Route::delete('delete_user', [ConsumerController::class,'delete_user']);

    // Categories Function
    Route::get('/Categories', [CategoriesController::class, 'index']);
    Route::post('Categories',[CategoriesController::class,'store']);
    Route::delete('Categories_Delete', [CategoriesController::class, 'delete']);

    // Product Function
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::delete('products', [ProductController::class, 'delete_product']);
    // Setting Function
    Route::post('settings', [SettingController::class, 'add']);
    Route::get('settings', [SettingController::class, 'get_all']);
    Route::delete('settings', [SettingController::class, 'deleteSetting']);



});
