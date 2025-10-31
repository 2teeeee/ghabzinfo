<?php

namespace App\Http\Controllers;

use App\Models\ElectricityAccount;
use App\Models\ElectricityBillPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Morilog\Jalali\Jalalian;

class ReportController extends Controller
{
    public function electricityDashboard(Request $request)
    {
        // گرفتن تاریخ آخرین دوره
        $lastPeriod = ElectricityBillPeriod::orderByDesc('current_date')->first();
        $lastDate = $lastPeriod ? Carbon::parse($lastPeriod->current_date) : now();

// تبدیل به Jalali
        $lastJalali = Jalalian::fromCarbon($lastDate);

// دریافت ماه و سال از request یا پیش‌فرض
        $month = $request->input('month', (int)$lastJalali->format('m')); // ماه شمسی
        $year  = $request->input('year', (int)$lastJalali->format('Y'));   // سال شمسی

// بازه میلادی
        $start = Jalalian::fromFormat('Y/m/d', sprintf('%04d/%02d/01', $year, $month))->toCarbon()->startOfMonth();
        $end   = (clone $start)->endOfMonth();

        // -------------------------------
        // 2️⃣ تعداد کنتورها
        // -------------------------------
        $counterCount = ElectricityAccount::whereHas('periods', function($q) use ($start, $end) {
            $q->whereBetween('current_date', [$start, $end]);
        })->count();

        // -------------------------------
        // 3️⃣ مبلغ برق تجمیعی و مصرف
        // -------------------------------
        $aggregates = ElectricityBillPeriod::whereBetween('current_date', [$start, $end])
            ->select(
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('SUM(CAST(average_consumption AS DECIMAL)) as total_consumption')
            )
            ->first();

        $totalAmount = $aggregates->total_amount ?? 0;
        $totalConsumption = $aggregates->total_consumption ?? 0;

        // -------------------------------
        // 4️⃣ مقایسه مبلغ قبوض برق در 3 سال گذشته
        // -------------------------------
        $yearlyComparison = [];
        $currentYear = Carbon::now()->year;
        for ($i = 0; $i < 3; $i++) {
            $y = $currentYear - $i;
            $sum = ElectricityBillPeriod::whereYear('current_date', $y)->sum('amount');
            $yearlyComparison[] = [
                'year' => $y,
                'amount' => $sum,
            ];
        }

        // -------------------------------
        // 5️⃣ کاهش مصرف ماه آخر نسبت به ماه مشابه سال قبل
        // -------------------------------
        $currentMonthAmount = ElectricityBillPeriod::whereBetween('current_date', [$start, $end])
            ->sum(DB::raw('CAST(average_consumption AS DECIMAL)'));

        $lastYearStart = (clone $start)->subYear();
        $lastYearEnd   = (clone $end)->subYear();

        $lastYearMonthAmount = ElectricityBillPeriod::whereBetween('current_date', [$lastYearStart, $lastYearEnd])
            ->sum(DB::raw('CAST(average_consumption AS DECIMAL)'));

        $consumptionDecrease = $lastYearMonthAmount > 0
            ? round(($lastYearMonthAmount - $currentMonthAmount) / $lastYearMonthAmount * 100, 2)
            : null;

        // -------------------------------
        // 6️⃣ مصرف فصلی برق (3 ماه اخیر)
        // -------------------------------
        $seasonData = collect();
        for ($i = 2; $i >= 0; $i--) {
            $tempStart = (clone $start)->subMonths($i)->startOfMonth();
            $tempEnd = (clone $tempStart)->endOfMonth();

            $seasonData->push([
                'month' => Jalalian::fromCarbon($tempStart)->format('Y/m'),
                'value' => ElectricityBillPeriod::whereBetween('current_date', [$tempStart, $tempEnd])
                    ->sum(DB::raw('CAST(average_consumption AS DECIMAL)')),
            ]);
        }

        // -------------------------------
        // نمایش view
        // -------------------------------
        return view('admin.reports.electricity_dashboard', compact(
            'month',
            'year',
            'counterCount',
            'totalAmount',
            'totalConsumption',
            'yearlyComparison',
            'consumptionDecrease',
            'seasonData'
        ));
    }
}
