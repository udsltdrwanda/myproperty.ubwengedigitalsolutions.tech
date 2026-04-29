<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class House extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'name', 'floor', 'description', 'building_value', 'construction_year'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function units()
    {
        return $this->hasMany(PropertyUnit::class, 'house_id');
    }
}
