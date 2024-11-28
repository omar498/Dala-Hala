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
    public function update(UploadImagesRequest $request)
{
    // Get the image ID from the request body
    $id = $request->input('id');

    // Retrieve the existing image record
    $image = Images::findOrFail($id);

    // Prepare validated data
    $validatedData = $request->validated();

    // Check if a new image file is uploaded
    if ($request->hasFile('image')) {
        // Generate a new path for the resized image
        $resizedMainImagePath = 'home/' . time() . '_' . $request->file('image')->getClientOriginalName();
        $resizedMainImage = Image::read($request->file('image'))->resize(500, 300);
        $resizedMainImage->save(storage_path('app/public/images/' . $resizedMainImagePath));

        // Update the image path in validated data
        $validatedData['homepage_image'] = $resizedMainImagePath;
    }

    // Update other validated fields
    $validatedData['image_title'] = $request->input('title');

    // Update the existing image record
    $image->update($validatedData);

    // Return a success response with the updated image
    return response()->json([
        'message' => 'Image updated successfully!',
        'data' => new ImageResource($image),
    ], 200);
}



}


