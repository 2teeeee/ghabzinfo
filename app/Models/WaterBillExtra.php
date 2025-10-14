<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterBillExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'water_bill_id', 'key', 'value'
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(WaterBill::class);
    }
}
