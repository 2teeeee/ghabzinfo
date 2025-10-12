<?php

namespace App\Services;

use App\Models\ElectricityBill;
use App\Models\GasBill;
use App\Models\User;
use App\Models\WaterBill;

class BillLimitService
{
    public function checkLimit(User $user, string $type): bool
    {
        $model = match ($type) {
            'electricity' => ElectricityBill::class,
            'gas' => GasBill::class,
            'water' => WaterBill::class,
        };

        $count = $model::where('user_id', $user->id)->count();

        return $count < $user->bill_limit || $user->hasRole('admin');
    }
}

