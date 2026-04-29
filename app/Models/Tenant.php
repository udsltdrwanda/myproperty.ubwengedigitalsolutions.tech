<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'landlord_id', 'tenant_id', 'tenant_name', 'company_tin', 'company_name', 'notes', 'phone', 'email', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }
}
