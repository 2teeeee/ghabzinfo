<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organ extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city_id'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
