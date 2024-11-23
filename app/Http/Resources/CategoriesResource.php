<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductHomeResource;
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
                //'image_path' => $this->image_path,
               'image_path'=> asset('storage/images/'.$this->image_path),
                'products' => ProductHomeResource::collection($this->whenLoaded('products')),

            ]
        ];
    }
}
