<?php

namespace App\Http\Requests;

use GuzzleHttp\Psr7\Message;
use Illuminate\Foundation\Http\FormRequest;

class CartCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'consumer_id' => 'required|exists:consumers,id',
            'product_id' => 'required|unique:carts|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];

    }
    public function messages()
    {
        return [
            'consumer_id.required' => 'Consumer ID is required.',
            'consumer_id.exists' => 'Consumer ID does not exist.',
            'product_id.required' => 'Product ID is required.',
            'product_id.unique' => 'Product is already in cart.',
            'product_id.exists' => 'Product ID does not exist.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
