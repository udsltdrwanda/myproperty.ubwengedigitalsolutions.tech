<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
