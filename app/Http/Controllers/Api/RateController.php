<?php

namespace App\Http\Controllers\Api;

use App\Models\Rate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RateStoreRequest;
use App\Http\Resources\RateResource;

class RateController extends Controller
{
    public function store(RateStoreRequest $request)
    {
        $validatedData = $request->validated();

        $rate = Rate::create($validatedData);
        return response()->json([
            'message' => 'thank you for rate our product',
            'data' =>new RateResource($rate) ,
        ], 201);
    }
}
