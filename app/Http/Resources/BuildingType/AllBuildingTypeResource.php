<?php

namespace App\Http\Resources\BuildingType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllBuildingTypeResource extends JsonResource
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
            'image' => (!empty($this->image)) ? request()->getSchemeAndHttpHost() . '/storage/' . $this->image : '',
        ];
    }
}
