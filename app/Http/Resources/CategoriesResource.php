<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[

            'categories' => [
                'id' => $this->id,
                'name' => $this->name,
                'image_path' => $this->image_path,
                'products' => ProductResource::collection($this->whenLoaded('products')),

            ]
        ];
    }
}
