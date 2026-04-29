<?php

namespace App\Models;
use App\Models\User;
use App\Models\PropertyUnit;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentRecords extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_no', 'user_id', 'payed_amount', 'payment_mode', 'payment_date', 'payment_reason', 'payment_proof'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'invoice_no');
    }

    public function paymentMode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode');
    }
}
