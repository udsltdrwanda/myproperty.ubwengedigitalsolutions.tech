<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseAdjacement extends Model
{
    protected $fillable = [
        'house_id',
        'landlord_id',
        'value_per_m',
        'tax_rate',
        'house_value',
        'year',
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class, 'house_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }
}
