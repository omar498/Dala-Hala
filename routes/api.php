<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoriesController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group(['prefix' => 'auth'], function ($router)
{
    Route::post('login', [AuthController::class ,'login']);
    Route::post('register', [AuthController::class ,'register']);
});

Route::middleware(['auth:api'])->group(function(){
      // Admin Function
      Route::post('refresh', [AuthController::class ,'refresh']);
      Route::post('me', [AuthController::class ,'me']);
      Route::post('logout', [AuthController::class ,'logout']);

    // Categories Function
    Route::get('Categories/GetAll', [CategoriesController::class, 'index']);
    Route::post('Categories/Store',[CategoriesController::class,'store']);
    Route::delete('Categories/Delete', [CategoriesController::class, 'destroy']);

    // Product Function
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/create', [ProductController::class, 'create']);
    Route::post('products', [ProductController::class, 'store']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);


});
