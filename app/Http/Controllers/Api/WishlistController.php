<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Consumer;
use App\Models\ConsumerWhislist;
use App\Http\Controllers\Controller;
use App\Http\Requests\WishlistRequest;
use App\Http\Resources\WishlistResource;
use App\Http\Resources\ProductHomeResource;

class WishlistController extends Controller
{
    public function addToWishlist(WishlistRequest $request)
    {
        $consumer = Consumer::findOrFail($request->consumer_id);
        $product = Product::findOrFail($request->product_id);

        // Check if the product is already in the consumer's wishlist
        if ($consumer->products()->where('product_id', $product->id)->exists()) {
            return response()->json([
                'message' => 'Product is already in the wishlist.',
            ], 409);
        }

        // Attach the product to the consumer
       $consumer->products()->attach($product);

        return response()->json([
            'data'=>new ProductHomeResource($product),
            'message' => 'Product added to wishlist successfully.',
        ]);
    }

    public function removeFromWishlist($id)
    {

        $wishlist = ConsumerWhislist::find($id);

        // Check if the wishlist item exists
        if (!$wishlist) {
            return response()->json(['error' => 'Wishlist item not found'], 404);
        }

        $wishlist->delete();
        return response()->json([
            'data'=>new WishlistResource( $wishlist),
            'message' => 'Wishlist item removed successfully'], 200);
    }

    public function showWishlist($consumerId)
{


 $wishlists = ConsumerWhislist::where('consumer_id', $consumerId)->get();

 return WishlistResource::collection($wishlists);

}

}
