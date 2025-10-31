<?php

namespace App\Services;

use App\Models\ElectricityBill;
use App\Models\GasBill;
use App\Models\User;
use App\Models\WaterBill;
use Carbon\Carbon;

class CommonService
{
    public static function normalizeDate(?string $date): ?string
    {
        if (empty($date)) return null;

        try {
            if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $date)) {
                return Carbon::createFromFormat('m/d/Y H:i:s', $date)->format('Y-m-d H:i:s');
            }

            return Carbon::parse($date)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }
}

