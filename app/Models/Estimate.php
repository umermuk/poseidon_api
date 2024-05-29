<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_type_id',
        'steep_roof_id',
        'currently_roof_id',
        'installed_roof_id',
        'when_start',
        'interested_financing',
        'address',
        'roof_size',
        'about',
        'name',
        'email',
        'phone',
        'type',
    ];

    public function building_type()
    {
        return $this->belongsTo(BuildingType::class, 'building_type_id', 'id');
    }

    public function steep_roof()
    {
        return $this->belongsTo(SteepRoof::class, 'steep_roof_id', 'id');
    }

    public function currently_roof()
    {
        return $this->belongsTo(Roof::class, 'currently_roof_id', 'id');
    }

    public function installed_roof()
    {
        return $this->belongsTo(Roof::class, 'installed_roof_id', 'id');
    }
}
