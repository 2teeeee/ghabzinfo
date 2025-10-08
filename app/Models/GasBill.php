<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GasBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'full_name', 'address', 'amount', 'bill_id', 'payment_id',
        'previous_date', 'current_date', 'payment_date', 'bill_pdf_url',
        'consumption_type','previous_counter_digit','current_counter_digit',
        'abonman','tax','insurance','status_code','status_description'
    ];

    public function extras(): HasMany
    {
        return $this->hasMany(GasBillExtra::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
