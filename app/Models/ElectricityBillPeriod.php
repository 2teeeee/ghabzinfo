<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

class ElectricityBillPeriod extends Model
{
    use SoftDeletes;

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



    public function getJalaliPreviousDateAttribute(): ?string
    {
        return $this->previous_date
            ? Jalalian::fromCarbon($this->previous_date)->format('Y/m/d')
            : null;
    }
    public function getJalaliCurrentDateAttribute(): ?string
    {
        return $this->current_date
            ? Jalalian::fromCarbon($this->current_date)->format('Y/m/d')
            : null;
    }
    public function getJalaliPaymentDateAttribute(): ?string
    {
        return $this->payment_date
            ? Jalalian::fromCarbon($this->payment_date)->format('Y/m/d')
            : null;
    }
    public function getJalaliCreatedAtAttribute(): ?string
    {
        return $this->created_at
            ? Jalalian::fromCarbon($this->created_at)->format('Y/m/d')
            : null;
    }
    public function getJalaliUpdatedAtAttribute(): ?string
    {
        return $this->created_at
            ? Jalalian::fromCarbon($this->updated_at)->format('Y/m/d')
            : null;
    }
    public function getJalaliDeletedAtAttribute(): ?string
    {
        return $this->created_at
            ? Jalalian::fromCarbon($this->deleted_at)->format('Y/m/d')
            : null;
    }
}
