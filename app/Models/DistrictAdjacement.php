<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DistrictAdjacement extends Model
{
    use HasFactory;

    protected $fillable = ['landlord_id', 'district_id', 'value_per_m', 'tax_rate', 'year'];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }


    public function districtRelation()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
