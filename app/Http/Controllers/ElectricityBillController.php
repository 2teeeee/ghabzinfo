<?php

namespace App\Http\Controllers;

use App\Models\ElectricityBill;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ElectricityBillController extends Controller
{
    public function index(Request $request): View
    {
        $query = ElectricityBill::query()->with('user');

        // فیلتر بر اساس کاربر
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // آخرین قبض هر bill_id (current_date بیشترین)
        $query->select('electricity_bills.*')
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')
                    ->from('electricity_bills')
                    ->groupBy('bill_id');
            });

        $bills = $query->latest('current_date')->paginate(15);

        $users = User::orderBy('name')->get();

        return view('admin.electricity_bills.index', compact('bills', 'users'));
    }

    public function show(ElectricityBill $bill): View
    {
        $bill->load('extras', 'user');

        return view('admin.electricity_bills.show', compact('bill'));
    }
}
