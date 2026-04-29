<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMode extends Model
{
    use HasFactory;
    protected $fillable = ['landlord_id', 'account_name', 'account_number'];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function paymentRecords()
    {
        return $this->hasMany(PaymentRecords::class, 'payment_mode', 'id');
    }
}
