<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'landlord_id',
        'unit_id',
        'tenant_id',
        'rent_record_id',
        'invoice_no',
        'amount',
        'start_date',
        'end_date',
        'vat',
        'duration_units',
        'invoice_status',
        'email_sent',
        'email_sent_count',
        'last_email_sent_at',
        'status',
        'due_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'due_date' => 'datetime',
        'last_email_sent_at' => 'datetime',
        'email_sent' => 'boolean',
        'status' => 'boolean',
        'amount' => 'decimal:2',
        'vat' => 'decimal:2'
    ];

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

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function rentrecord()
    {
        return $this->belongsTo(RentRecord::class, 'rent_record_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PaymentRecords::class, 'invoice_no', 'invoice_no');
    }

    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->vat;
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->payments->sum('payed_amount');
    }

    public function ebmInvoice()
    {
        return $this->hasOne(EbmInvoice::class, 'invoice_no', 'invoice_no');
    }
}
