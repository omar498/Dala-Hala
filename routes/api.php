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
use App\Http\Controllers\Api\Pages\HomePageController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\Pages\ProductPageController;
use App\Http\Controllers\Api\Pages\CategoryPageController;
use App\Http\Controllers\Api\Pages\OrderController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




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

    Route::Post('AddToCart', [CartController::class, 'addToCart']);
    Route::Post('show', [CartController::class, 'show']);
    Route::Post('order', [CartController::class, 'order']);
    Route::Post('Remove_From_Cart', [CartController::class, 'remove_from_cart']);
    Route::Post('Destroy_Cart', [CartController::class, 'destroyCart']);
    Route::post('rates', [RateController::class, 'store']);

        // app pages
    Route::get('product_page', [ProductPageController::class, 'show']);
    Route::Post('order_page', [OrderController::class, 'order']);
    Route::get('catrgory_page', [CategoryPageController::class, 'show']);
    Route::get('home_page', [HomePageController::class, 'index']);


    Route::post('wishlist', [WishlistController::class, 'addToWishlist']);
    Route::delete('wishlist/{id}', [WishlistController::class, 'removeFromWishlist']);
    Route::get('wishlist/{consumer}', [WishlistController::class, 'showWishlist']);

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
    Route::post('home_image', [ImageUploadController::class, 'upload']);

    Route::get('settings', [SettingController::class, 'get_all']);
    Route::delete('settings', [SettingController::class, 'deleteSetting']);



});
