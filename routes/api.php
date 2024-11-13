<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ConsumerController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ConsumerCartController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group(['prefix' => 'auth'], function ($router)
{
    Route::post('login', [AuthController::class ,'loginn']);
    Route::post('register', [AuthController::class ,'register']);
    // Consumer Function


});
// User Auth
Route::group(['prefix' => 'user'], function ($router){
    Route::post('login', [ConsumerController::class ,'login']);
    Route::post('register', [ConsumerController::class ,'register']);
});

Route::middleware(['auth:consumer-api'])->group(function(){
    Route::post('refresh_user_token', [ConsumerController::class, 'refreshToken']);
    Route::post('user_logout', [ConsumerController::class ,'logout']);

});



Route::middleware(['auth:api'])->group(function(){
    // Admin Function
    Route::post('refresh_token', [AuthController::class, 'refreshToken']);
    Route::post('logout', [AuthController::class ,'logout']);
    Route::delete('remove', [AuthController::class,'delete_admin']);
    // Manage Users Functions
    Route::delete('delete_user', [ConsumerController::class,'delete_admin']);

    // Categories Function
    Route::get('/Categories', [CategoriesController::class, 'index']);
    Route::post('Categories',[CategoriesController::class,'store']);
    Route::delete('Categories/Delete', [CategoriesController::class, 'destroy']);

    // Product Function
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::delete('products', [ProductController::class, 'delete_product']);
    // Setting Function
    Route::post('settings', [SettingController::class, 'add']);
    Route::get('settings', [SettingController::class, 'get_all']);
    Route::delete('settings', [SettingController::class, 'deleteSetting']);
    Route::Post('createCart', [ConsumerCartController::class, 'createCart']);



});