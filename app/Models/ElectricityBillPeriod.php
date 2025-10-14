<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectricityBillPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'electricity_account_id', 'amount', 'payment_id', 'previous_date', 'current_date',
        'payment_date', 'bill_pdf_url', 'sale_year', 'cycle', 'average_consumption',
        'insurance_amount', 'tax_amount', 'paytoll_amount', 'power_paytoll_amount',
        'total_days', 'status_code', 'status_description'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(ElectricityAccount::class, 'electricity_account_id');
    }

    public function extras(): HasMany
    {
        return $this->hasMany(ElectricityBillExtra::class);
    }
}
