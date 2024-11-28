<?php

namespace App\Http\Controllers\Api;


use Stripe\Stripe;
use App\Models\Cart;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class PaymentController extends Controller
{
/*     public function showPaymentForm()
{
    $cartItems = Cart::where('consumer_id', auth('consumer-api')->id())->with('product')->get(); // Fetch cart items for the logged-in user
    return view('payment', compact('cartItems'));
}

    public function processPayment(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'payment_method_id' => 'required|string',
            'cart_id' => 'required|exists:carts,id', // Assuming you pass cart_id
        ]);

        $cart = Cart::where('consumer_id', $request->cart_id)->get();

        // Calculate the total amount
        $totalAmount = $cart->sum(function ($item) {
            return $item->product->price * $item->quantity; // Assuming price is a field in your Product model
        });

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            // Payment successful; store a success message in the session
            $request->session()->flash('success', 'Payment successful!');

            return redirect()->route('payment.success');
        } catch (\Exception $e) {
            // Payment failed; store an error message in the session
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('payment.failure');
        }
    }
 */

    public function test (Request $request)
    {
        try{

            $stripe = new \Stripe\StripeClient
            (env('STRIPE_SECRET'));

            $res=$stripe->tokens->create([
                'card' => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_number,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                ],
            ]);
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $response=$stripe->charges->create([
                'amount' => $request->amount,
                'currency' => 'usd',
                'source' => $res->id,
                'description'=>$request->description,
            ]);
            return response()->json([$response->status],201);
        }
        catch(Exception $ex)
        {
            return response()->json([['response'=>'Error']],500);
        }
    }

}
