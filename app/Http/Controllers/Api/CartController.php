<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Consumer;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartShowRequest;

use Illuminate\Validation\ValidationException;

class CartController extends Controller
{

    public function addToCart(CartAddRequest $request)
    {

        $validatedData = $request->validated();

        $consumer = Consumer::findOrFail($validatedData['consumer_id']);
        $product = Product::findOrFail($validatedData['product_id']);

        // Check if the requested quantity exceeds available stock
        if ($validatedData['quantity'] > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => ['The quantity exceeds the available stock for this product.'],
            ]);
        }

        // Check if a soft-deleted cart item exists for this consumer and product
        $cart = Cart::withTrashed()
            ->where('product_id', $validatedData['product_id'])
            ->where('consumer_id', $validatedData['consumer_id'])
            ->first();

        if ($cart) {
            // If it exists and is soft-deleted, restore it
            if ($cart->trashed()) {
                $cart->restore();
            }

            // Check if the cart item already exists
            $cartItem = $consumer->cart()->where('product_id', $validatedData['product_id'])->first();

            if ($cartItem) {

                // Update the quantity if it exists

                $newQuantity = $cartItem->quantity +  $validatedData['quantity'];
                if ($newQuantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'quantity' => ['The updated quantity exceeds the available stock for this product.'],
                    ]);
                }
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            } else {
                // Create a new cart item if it doesn't exist
                $cartItem = $consumer->cart()->create([
                    'product_id' => $validatedData['product_id'],
                    'quantity' => $validatedData['quantity'],
                ]);
            }

            // Decrease the product stock by the added quantity
            $product->stock -= $validatedData['quantity'];
            $product->save();

            return response()->json([
                'message' => 'Product added to cart successfully!',
                'data' => new CartResource($cartItem),
            ], Response::HTTP_CREATED);
        }
    }


    public function remove_from_cart(CartAddRequest $request)
    {
        $validatedData = $request->validated();

        $consumer = Consumer::findOrFail($validatedData['consumer_id']);
        
        $product = Product::findOrFail($validatedData['product_id']);
        $cartItem = $consumer->cart()->where('product_id', $validatedData['product_id'])->first();

        if ($cartItem) {
            // Check if the quantity to remove is greater than or equal to the current quantity
            if ($validatedData['quantity'] >= $cartItem->quantity) {
                // Delete the cart item
                $cartItem->delete();

                // Increase stock by the quantity of the removed product
                $product->stock += $cartItem->quantity; // Add the entire quantity back to stock
                $product->save();

                return response()->json([
                    'message' => 'Product removed from cart successfully!',
                    'data' => null,
                ], Response::HTTP_OK);
            } else {
                // Update the quantity if it exists
                $newQuantity = $cartItem->quantity - $validatedData['quantity'];

                if ($newQuantity < 0) {
                    throw ValidationException::withMessages([
                        'quantity' => ['The updated quantity cannot be negative.'],
                    ]);
                }

                // Update the cart item quantity
                $cartItem->quantity = $newQuantity;
                $cartItem->save();

                // Increase stock by the quantity removed
                $product->stock += $validatedData['quantity'];
                $product->save();

                return response()->json([
                    'message' => 'Product quantity updated successfully!',
                    'data' => new CartResource($cartItem),
                ], Response::HTTP_OK);
            }
        }

        // If no cart item found
        return response()->json([
            'message' => 'Product not found in cart.',
        ], Response::HTTP_NOT_FOUND);
    }

    public function destroyCart(CartShowRequest $request)
    {
        $validatedData = $request->validated();


        $consumer = Consumer::findOrFail($validatedData['consumer_id']);
        $consumer->cart()->delete();


        return response()->json([
            'message' => 'All items removed from cart successfully!',
            $consumer->cart()->onlyTrashed()->count(),
        ], Response::HTTP_OK);
    }


    public function show()
    {
        $cart = Cart::onlyTrashed()->get();
        return response()->json([
            'data' => $cart,
        ]);
    }
}
