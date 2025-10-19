<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GasBillExtra extends Model
{

    protected $fillable = [
        'gas_bill_period_id', 'key', 'value'
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(GasBillPeriod::class, 'gas_bill_period_id');
    }
}
