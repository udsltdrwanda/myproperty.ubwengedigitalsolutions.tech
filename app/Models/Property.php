<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Property extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'upi', 'name', 'bankInterest', 'landlord_id', 'description', 'property_value', 'country', 'province', 'district', 'sector', 'cell', 'village', 'property_use', 'area', 'owned_year', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }
    public function provinceRelation()
    {
        return $this->belongsTo(Province::class, 'province');
    }
    public function districtRelation()
    {
        return $this->belongsTo(District::class, 'district');
    }

    public function sectorRelation()
    {
        return $this->belongsTo(Sector::class, 'sector');
    }
    public function cellRelation()
    {
        return $this->belongsTo(Cell::class, 'cell');
    }
    public function villageRelation()
    {
        return $this->belongsTo(Village::class, 'village');
    }
    public function houses()
    {
        return $this->hasMany(House::class);
    }
}
