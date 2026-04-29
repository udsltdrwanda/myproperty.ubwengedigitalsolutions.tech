<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyUnit extends Model
{
    use HasFactory;

    protected $fillable = ['house_id', 'amenities', 'name', 'type', 'floor', 'roomNumber', 'notes', 'rent', 'unit_status', 'rentTypes'];

    protected $casts = [
        'amenities' => 'array',
    ];

    public function house()
    {
        return $this->belongsTo(House::class, 'house_id');
    }

    public function activeRentRecord()
    {
        return $this->hasOne(RentRecord::class, 'unit_id')->whereDate('end_date', '>=', now());
    }
}
