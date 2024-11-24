<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductHomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return[

            'id' => $this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'price'=>$this->price,
            'stock'=>$this->stock,
            'main_image'=> asset('storage/images/'.$this->main_image_path),
            'ratings' => $this->rates->pluck('rate'), // Assuming 'rating' is the field in Rate model
            'comments' => $this->rates->pluck('comment'),
        ];
    }
}
