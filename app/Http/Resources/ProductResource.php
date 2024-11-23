<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CategoriesResource;
use App\Models\Categories;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[

           // 'category' => new CategoriesResource($this->whenLoaded('category')),

            'id' => $this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'price'=>$this->price,
            'stock'=>$this->stock,
            'category_id'=>$this->category_id,
             'main_image'=> asset('storage/images/'.$this->main_image_path),

             //Return array of Image
             'images' => array_map(function ($imagePath) {
            return asset('storage/images/' . $imagePath);
        }, json_decode($this->image_path, true)),
      ];
    }
}
