<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'province_id',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function sectors():HasMany
    {
        return $this->hasMany(Sector::class, 'district_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'district');
    }
}
