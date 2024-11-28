<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'array',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The product name is required.',
            'price.required' => 'The product price is required.',
            'image.*.image' => 'Each file must be an image.',
            'image.*.mimes' => 'Images must be of type jpeg, png, jpg, or gif.',
            'main_image.image' => 'The main image must be an image.',
            'main_image.mimes' => 'The main image must be of type jpeg, png, jpg, or gif.',
        ];
    }
}
