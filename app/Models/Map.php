<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'zoom',
        'description',
        'map_type',
        'polygon',
    ];
}
