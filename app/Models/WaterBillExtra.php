<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterBillExtra extends Model
{
    protected $fillable = [
        'water_bill_id', 'key', 'value'
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(WaterAccount::class);
    }
}
