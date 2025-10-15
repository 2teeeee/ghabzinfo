<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectricityBillExtra extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'electricity_bill_period_id',
        'key',
        'value',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(ElectricityBillPeriod::class, 'electricity_bill_period_id');
    }
}
