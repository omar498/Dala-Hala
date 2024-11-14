<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartCreateRequest;
use App\Http\Requests\CartShowRequest;
use App\Http\Resources\CartResource;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
public function makeCart(CartCreateRequest $request)
{

    $validatedData = $request->validated();

 $product = Product::findOrFail($validatedData['product_id']);

 // Check if the requested quantity exceeds stock
 if ($validatedData['quantity'] > $product->stock) {
     throw ValidationException::withMessages([
         'quantity' => ['The quantity exceeds the available stock for this product.'],
     ]);
 }
   $product->stock -= $validatedData['quantity'];  // Decrease stock by the quantity added to the cart
   $product->save();
 $cart = Cart::create($validatedData);

          return response()->json([
              'message' => 'Cart Created Successfully!',
              'data' => new  CartResource($cart),
          ], Response::HTTP_CREATED);
 }

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

        // Check if the cart item already exists
        $cartItem = $consumer->cart()->where('product_id', $validatedData['product_id'])->first();

        if ($cartItem) {
            // Update the quantity if it exists
            $newQuantity = $cartItem->quantity + $validatedData['quantity'];
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

    public function showCart(CartShowRequest $request)
{
    $validatedData = $request->validated();

    $cartItems = Cart::where('consumer_id', $validatedData['consumer_id'])->get();
    $cartDetails = $cartItems->map(function ($item) {
        $payment =$item->quantity * $item->product->price;
        return [
            'product_name' => $item->product->name,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
            'total_price' => $payment,
        ];
    });
    return response()->json([
        'message' => 'Cart retrieved successfully!',
        'data' => $cartDetails,
    ], Response::HTTP_OK);
}


}







