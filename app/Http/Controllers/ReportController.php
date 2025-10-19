<?php

namespace App\Http\Controllers;

use App\Models\ElectricityBillExtra;
use App\Models\ElectricityBillPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function electricityBillLastMonth(): View
    {
        // انتخاب آخرین ماه (بر اساس بیشترین current_date)
        $lastMonth = ElectricityBillPeriod::max('current_date');

        // جمع کل مبلغ قبوض هر مرکز در آخرین ماه
        $baseQuery = DB::table('electricity_bill_periods as p')
            ->join('electricity_accounts as a', 'p.electricity_account_id', '=', 'a.id')
            ->leftJoin('centers as c', 'a.center_id', '=', 'c.id')
            ->select(
                'c.name as center_name',
                DB::raw('SUM(p.amount) as total_amount'),
                DB::raw('MAX(p.id) as latest_period_id')
            )
            ->where('p.current_date', $lastMonth)
            ->groupBy('c.id', 'c.name')
            ->get();

        // دریافت extras برای آخرین دوره‌ها
        $extras = ElectricityBillExtra::whereIn('electricity_bill_period_id', $baseQuery->pluck('latest_period_id'))
            ->whereIn('key', ['مصرف کم باری', 'مصرف میان باری', 'مصرف پر باری'])
            ->get()
            ->groupBy('electricity_bill_period_id');

        // تجمیع اطلاعات نهایی
        $reports = $baseQuery->map(function ($item) use ($extras) {
            $item->low_amount = 0;
            $item->mid_amount = 0;
            $item->peak_amount = 0;

            if (isset($extras[$item->latest_period_id])) {
                foreach ($extras[$item->latest_period_id] as $extra) {
                    switch ($extra->key) {
                        case 'مصرف کم باری':
                            $item->low_amount = (int) $extra->value;
                            break;
                        case 'مصرف میان باری':
                            $item->mid_amount = (int) $extra->value;
                            break;
                        case 'مصرف پر باری':
                            $item->peak_amount = (int) $extra->value;
                            break;
                    }
                }
            }

            return $item;
        });

        return view('admin.reports.electricity_last_month', compact('reports', 'lastMonth'));
    }
}
