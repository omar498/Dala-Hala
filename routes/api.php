<?php

use App\Http\Controllers\Api\CategoriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group(['prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class ,'login']);
    Route::post('register', [AuthController::class ,'register']);
    Route::post('Categories/Store',[CategoriesController::class,'store']);
    Route::get('Categories/GetAll', [CategoriesController::class, 'index']);
    Route::delete('Categories/Delete', [CategoriesController::class, 'destroy']);

});
Route::middleware(['auth:api'])->group(function(){

    Route::post('refresh', [AuthController::class ,'refresh']);
    Route::post('me', [AuthController::class ,'me']);
    Route::post('logout', [AuthController::class ,'logout']);
});
