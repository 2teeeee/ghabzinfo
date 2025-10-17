<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Organ;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrganController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // اگر city هستیم فقط سازمان‌های شهر خود را ببینیم
        $organs = Organ::with('city')
            ->when($user->hasRole('city'), fn($q) => $q->where('city_id', $user->city_id))
            ->latest()
            ->paginate(15);

        return view('admin.organs.index', compact('organs'));
    }

    public function create(): View
    {
        $user = auth()->user();

        $cities = City::when($user->hasRole('city'), fn($q) => $q->where('id', $user->city_id))
            ->get();

        return view('admin.organs.create', compact('cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
        ]);

        Organ::create($request->only('city_id', 'name'));

        return redirect()->route('admin.organs.index')->with('success', 'سازمان با موفقیت ایجاد شد.');
    }

    public function edit(Organ $organ): View
    {
        $user = auth()->user();

        $cities = City::when($user->hasRole('city'), fn($q) => $q->where('id', $user->city_id))
            ->get();

        return view('admin.organs.edit', compact('organ', 'cities'));
    }

    public function update(Request $request, Organ $organ): RedirectResponse
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
        ]);

        $organ->update($request->only('city_id', 'name'));

        return redirect()->route('admin.organs.index')->with('success', 'سازمان با موفقیت بروزرسانی شد.');
    }

    public function destroy(Organ $organ): RedirectResponse
    {
        $organ->delete();
        return redirect()->route('admin.organs.index')->with('success', 'سازمان حذف شد.');
    }
}
