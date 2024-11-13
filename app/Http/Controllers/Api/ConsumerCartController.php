<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Consumer;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ConsumerCartController extends Controller
{
    public function createCart(Consumer $consumer)
    {
        // Check if the consumer already has a cart
        $existingCart = $consumer->cart;

        if (!$existingCart) {
            // Create a new cart for the consumer
            $cart = new Cart;
            $cart->consumer()->associate($consumer);
            $cart->save();

            return response()->json([
                'message' => 'Cart created successfully',
                'cart' => $cart
            ], Response::HTTP_CREATED);
        } else {
            // Redirect to the existing cart or show an error message
            return response()->json([
                'message' => 'You already have a cart',
                'cart' => $existingCart
            ], Response::HTTP_OK);
        }
    }
}
