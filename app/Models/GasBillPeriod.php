<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasBillPeriod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'gas_account_id', 'amount', 'payment_id', 'previous_date', 'current_date',
        'payment_date', 'bill_pdf_url', 'consumption_type', 'previous_counter_digit', 'current_counter_digit',
        'abonman', 'tax', 'insurance', 'status_code', 'status_description'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(GasAccount::class, 'gas_account_id');
    }

    public function extras(): HasMany
    {
        return $this->hasMany(GasBillExtra::class);
    }
}
