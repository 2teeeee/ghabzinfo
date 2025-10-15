<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterBillPeriod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'water_account_id', 'amount', 'payment_id', 'previous_date', 'current_date',
        'payment_date', 'bill_pdf_url', 'status_code', 'status_description'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(WaterAccount::class, 'water_account_id');
    }

    public function extras(): HasMany
    {
        return $this->hasMany(WaterBillExtra::class);
    }
}
