<?php

namespace App\Http\Controllers\Api;

use App\Models\Images;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Requests\UploadImagesRequest;
use Intervention\Image\Laravel\Facades\Image;

class ImageUploadController extends Controller
{
    public function upload(UploadImagesRequest $request)
    {
        $validatedData  = $request->validated();

        if ($request->hasFile('image')) {

            $resizedMainImagePath = 'home/' . time() . '_' . $request->file('image')->getClientOriginalName();
            $resizedMainImage = Image::read($request->file('image'))->resize(500, 300);
            $resizedMainImage->save(storage_path('app/public/images/' . $resizedMainImagePath));



        }
        $validatedData ['homepage_image'] = $resizedMainImagePath;
        $validatedData['image_title'] = $request->input('title');

        $product = Images::create($validatedData);

        return response()->json([
            'message' => 'Image added successfully!',
            'data' => new ImageResource($product),
        ], 200);
    }



}


