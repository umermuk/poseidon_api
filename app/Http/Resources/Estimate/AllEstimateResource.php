<?php

namespace App\Http\Resources\Estimate;

use App\Http\Resources\BuildingType\AllBuildingTypeResource;
use App\Http\Resources\Roof\AllRoofResource;
use App\Http\Resources\SteepRoof\AllSteepRoofResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllEstimateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $estimate = ((array) $this)['resource']->toArray();
        return [
            'id' => $this->id ?? '',
            'type' => $this->type ?? '',
            'name' => $this->name ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'address' => $this->address ?? '',
            'roof_size' => $this->roof_size ?? '',
            'about' => $this->about ?? '',
            'when_start' => $this->when_start ?? '',
            'interested_financing' => $this->interested_financing ?? '',
            $this->mergeWhen((!empty($this->building_type) && isset($estimate['building_type'])), [
                'building_type' => (!empty($this->building_type) && isset($estimate['building_type'])) ? new AllBuildingTypeResource($this->building_type) : '',
            ]),
            $this->mergeWhen((!empty($this->steep_roof) && isset($estimate['steep_roof'])), [
                'steep_roof' => (!empty($this->steep_roof) && isset($estimate['steep_roof'])) ? new AllSteepRoofResource($this->steep_roof) : '',
            ]),
            $this->mergeWhen((!empty($this->currently_roof) && isset($estimate['currently_roof'])), [
                'currently_roof' => (!empty($this->currently_roof) && isset($estimate['currently_roof'])) ? new AllRoofResource($this->currently_roof) : '',
            ]),
            $this->mergeWhen((!empty($this->installed_roof) && isset($estimate['installed_roof'])), [
                'installed_roof' => (!empty($this->installed_roof) && isset($estimate['installed_roof'])) ? new AllRoofResource($this->installed_roof) : '',
            ]),
        ];
    }
}
