<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterBillExtra extends Model
{
    protected $fillable = [
        'water_bill_period_id', 'key', 'value'
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(WaterBillPeriod::class, 'water_bill_period_id');
    }
}
