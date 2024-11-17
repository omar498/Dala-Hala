<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CategoriesResource;
use App\Models\Categories;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[


            'id' => $this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'price'=>$this->price,
            'image_path'=>$this->image_path,
            'stock'=>$this->stock,
            'category_id'=>$this->category_id,
            'image_path'=>$this->image_path,
            'ratings' => $this->rates->take(3)->map(function ($rate) {
                return [
                    'score' => $rate->rate,
                    'comment' => $rate->comment,
                ];
            }),
        ];
}
}
