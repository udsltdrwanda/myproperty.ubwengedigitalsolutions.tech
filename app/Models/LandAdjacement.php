<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandAdjacement extends Model
{
    protected $fillable = ['property_id', 'landlord_id', 'value_per_m', 'tax_rate', 'land_value', 'year'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }
}
