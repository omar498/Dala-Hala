<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartShowRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CartCreateRequest;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{

    public function addToCart(CartAddRequest $request)
    {

        $validatedData = $request->validated();

        // Find the consumer
        $consumer = Consumer::findOrFail($validatedData['consumer_id']);
        // Find the product
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
    ->where('consumer_id', $validatedData['consumer_id']) // Assuming consumer_id is passed in request
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

            $newQuantity = $cartItem->quantity+  $validatedData['quantity'];
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

    public function order(CartShowRequest $request)
    {
        $validatedData = $request->validated();

        $cartItems = Cart::where('consumer_id', $validatedData['consumer_id'])->get();
        $cartDetails = $cartItems->map(function ($item) {
            $payment = $item->quantity * $item->product->price;
            return [
                'Product_id' => $item->product->id,
                'product_name' => $item->product->name,
                'product_image' => asset('storage/images/' . $item->product->main_image_path),
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'total_price' => $payment,
            ];
        });
        // Calculate the total of all total_prices
        $totalAmount = $cartDetails->sum('total_price');


        $discountRate = 10; // Example: 10% discount
        $discountAmount = ($totalAmount * $discountRate) / 100;
        $finalPrice = $totalAmount - $discountAmount;
        return response()->json([
            'message' => 'Cart retrieved successfully!',
            'data' => $cartDetails,
            'total_amount' => $totalAmount, // Total before discount
            'discount_amount' => $discountAmount, // Amount discounted
            'final_price' => $finalPrice, // Total after discount
        ], Response::HTTP_OK);
    }

    public function remove_from_cart(CartAddRequest $request)
    {
        $validatedData = $request->validated();
        // Find the consumer
        $consumer = Consumer::findOrFail($validatedData['consumer_id']);
        // Find the product
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


    public function show(){
        $cart=Cart::onlyTrashed()->get();
        return response()->json([
            'data'=> $cart ,
        ]);
    }
}
