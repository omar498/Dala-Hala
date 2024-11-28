<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('process-payment', [PaymentController::class,'processPayment'])->name('process.payment');

Route::get('/payment/success', function () {
    return view('payment-success');
})->name('payment.success');

Route::get('/payment/failure', function () {
    return view('payment-failure');
})->name('payment.failure');
