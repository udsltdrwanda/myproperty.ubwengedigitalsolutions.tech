<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentRecord extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','landlord_id', 'tenant_id', 'unit_id', 'amount','vat', 'start_date', 'end_date', 'duration_time', 'agreement_document', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }
    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id');
    }
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
