<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Cell extends Model
{
    use HasFactory;
    public $table = 'cells';

    public $fillable = [
        'name',
        'sector_id',
    ];

    public function sector():BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'cell_id');
    }
}
