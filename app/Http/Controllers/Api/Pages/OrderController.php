<?php

namespace App\Http\Controllers\Api\Pages;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Setting;
use Stripe\PaymentIntent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartShowRequest;
use App\Http\Resources\SettingResource;

class OrderController extends Controller
{
    public function order(CartShowRequest $request)
    {
        $validatedData = $request->validated();
        $settings = Setting::all();

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

       /*  Stripe::setApiKey(env('STRIPE_SECRET'));
        $intent = PaymentIntent::create([
            'amount' => $finalPrice * 100, // Convert to cents
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]); */
        return response()->json([
            'message' => 'Order retrieved successfully!',
            'data' => $cartDetails,
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            /*'client_secret' => $intent->client_secret, */
            'footer'=>SettingResource::collection($settings)


        ],200);
    }
}
