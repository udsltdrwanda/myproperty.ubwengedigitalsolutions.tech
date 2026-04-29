<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Village extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'cell_id',
    ];

    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'cell_id');
    }
}
