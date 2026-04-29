<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitAmenities extends Model
{
    use HasFactory;
    protected $fillable = ['property_id', 'landlord_id', 'name'];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
