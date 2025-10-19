<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'city_id', 'organ_id', 'unit_id', 'center_id',
        'bill_id', 'full_name', 'address'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function organ(): BelongsTo
    {
        return $this->belongsTo(Organ::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function periods(): HasMany
    {
        return $this->hasMany(WaterBillPeriod::class);
    }

    public function latestPeriod(): HasOne
    {
        return $this->hasOne(WaterBillPeriod::class)->latestOfMany();
    }
}
