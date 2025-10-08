<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectricityBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'full_name', 'address', 'amount', 'bill_id', 'payment_id',
        'previous_date', 'current_date', 'payment_date', 'bill_pdf_url',
        'tariff_type', 'customer_type', 'sale_year', 'cycle',
        'average_consumption', 'insurance_amount', 'tax_amount',
        'paytoll_amount', 'power_paytoll_amount', 'total_days',
        'status_code', 'status_description'
    ];

    public function extras(): HasMany
    {
        return $this->hasMany(ElectricityBillExtra::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
