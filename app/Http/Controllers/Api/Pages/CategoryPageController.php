<?php

namespace App\Http\Controllers\Api\Pages;


use App\Models\Setting;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Http\Resources\CategoriesResource;

class CategoryPageController extends Controller
{
    public function show(Request $request)
    {
        $settings = Setting::all();
        $ratingFilter = $request->query('rate');
        $rate = $request->input('id');

        try{
            $category = Categories::with(['products' => function ($query) use ($ratingFilter) {
                if ($ratingFilter) {
                    // Join with rates to filter products by rating
                    $query->whereHas('rates', function ($q) use ($ratingFilter) {
                        $q->where('rate', /* '<=', */ $ratingFilter);
                        // Filter by the  actual rate and greater than
                    });
                }
                $query->take(6);

            }])->findOrFail($rate);

            return response([
                'message' => 'Category retrieved successfully',
                'data' => new CategoriesResource($category),
                'footer'=>SettingResource::collection($settings),
            ],200);


        }

        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }






    }
}
