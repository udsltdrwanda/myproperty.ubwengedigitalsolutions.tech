<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EbmInvoice extends Model
{

    use HasFactory;
    protected $fillable = ['invoice_no', 'invoice_description', 'invoice_attachment', 'invoice_status'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_no', 'invoice_no');
    }

}
