<?php

namespace App\Http\Resources\Roof;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllRoofResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'price' => (!empty($this->extra) && !empty($this->extra->size)) ? ($this->price) * ((int) $this->extra->size) : $this->price,
            'desc' => $this->desc ?? '',
            'image' => (!empty($this->image)) ? request()->getSchemeAndHttpHost() . '/storage/' . $this->image : '',
        ];
    }
}
