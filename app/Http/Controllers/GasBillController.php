<?php

namespace App\Http\Controllers;

use App\Models\GasBill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class GasBillController extends Controller
{
    public function index(Request $request): View
    {
        $query = GasBill::query()->with('user');

        // فیلتر بر اساس کاربر
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // آخرین قبض هر bill_id (current_date بیشترین)
        $query->select('gas_bills.*')
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')
                    ->from('gas_bills')
                    ->groupBy('bill_id');
            });

        $bills = $query->latest('current_date')->paginate(15);

        $users = User::orderBy('name')->get();

        return view('admin.gas_bills.index', compact('bills', 'users'));
    }

    public function show(GasBill $bill): View
    {
        $bill->load('extras', 'user');

        return view('admin.gas_bills.show', compact('bill'));
    }
}
