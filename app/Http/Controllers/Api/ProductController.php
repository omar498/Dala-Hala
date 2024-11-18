<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductStoreRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;



class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();

        return response()->json([
            'message' => 'المنتجات',
        'data' =>ProductResource::collection($product)
        ], 200);
    }

    public function store(ProductStoreRequest $request)
    {

        $productData = $request->validated();
        if ($request->hasFile('image')) {
            $imagePaths = [];

            foreach ($request->file('image') as $image) {
                $imagePath = $image->store('Product', 'images'); // Store original image
                $imagePaths[] = $imagePath;
            }

        $productData['image_path'] = json_encode($imagePaths);

        // Handle the main image
    if ($request->hasFile('main_image')) {
        $mainImagePath = $request->file('main_image')->store('Product/Main', 'images');
        $productData['main_image_path'] = $mainImagePath; // Store main image path
    }

        $product = Product::create($productData);


        return response()->json([
            'message' => 'Product added successfully!',
            'data' => new ProductResource($product),
            200
        ]);
    }
}

     public function delete_product(Request $request)
    {
        try{
        $id = $request->input('id');

        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
        'message' => 'Product deleted successfully',
        'data'=>new ProductResource($product),

         200]);
        }

        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    public function resizeImage($image, $width, $height) {
        $resizedImage =($image)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg', 80);

        $imagePath = $resizedImage->store('products'); // Store the resized image

        return $imagePath;
    }
}

