<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GasBillExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'gas_bill_id', 'key', 'value'
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(GasBill::class);
    }
}
