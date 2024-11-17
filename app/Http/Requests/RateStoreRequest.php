<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RateStoreRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'rate' => 'required|numeric|between:1,5', // Rate should be between 1 and 5
            'comment' => 'nullable|string|max:255',
        ];
    }
}
