<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Intervenion\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductStoreRequest;


class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();

        return response()->json([
            'message' => 'المنتجات',
            'data' => ProductResource::collection($product)
        ], 200);
    }


    public function store(ProductStoreRequest $request)
    {
        $productData = $request->validated();
        $imagePaths = [];

        // Check if the request has multiple images
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $resizedImagePath = 'Product/' . time() . '_' . $image->getClientOriginalName();
                $resizedImage = Image::read($image)->resize(300, 200);
                $resizedImage->save(storage_path('app/public/images/' . $resizedImagePath));
                $imagePaths[] = $resizedImagePath;
            }

            $productData['image_path'] = json_encode($imagePaths);
        }

        // Handle the main image
        if ($request->hasFile('main_image')) {
            $resizedMainImagePath = 'Product/Main/' . time() . '_' . $image->getClientOriginalName();
            $resizedMainImage = Image::read($request->file('main_image'))->resize(500, 300);
            $resizedMainImage->save(storage_path('app/public/images/' . $resizedMainImagePath));

            $productData['main_image_path'] = $resizedMainImagePath;
        }

        // Create the product
        $product = Product::create($productData);

        return response()->json([
            'message' => 'Product added successfully!',
            'data' => new ProductResource($product),
        ], 200);
    }

    public function delete_product(Request $request)
    {
        try {
            $id = $request->input('id');

            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully',
                'data' => new ProductResource($product),

                200
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
