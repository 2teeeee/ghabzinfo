<?php
namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = Unit::query()->with('organ.city');

        if ($user->hasRole('city')) {
            $query->whereHas('organ.city', fn($q) => $q->where('id', $user->city_id));
        } elseif ($user->hasRole('organ')) {
            $query->where('organ_id', $user->organ_id);
        }

        $units = $query->latest()->paginate(15);

        return view('admin.units.index', compact('units'));
    }

    public function create(): View
    {
        $cities = City::all();
        return view('admin.units.create', compact('cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'organ_id' => 'required|exists:organs,id',
            'name' => 'required|string|max:255',
        ]);

        Unit::create([
            'organ_id' => $request->organ_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.units.index')->with('success', 'واحد با موفقیت ایجاد شد.');
    }

    public function edit(Unit $unit): View
    {
        $cities = City::all();
        return view('admin.units.edit', compact('unit', 'cities'));
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'organ_id' => 'required|exists:organs,id',
            'name' => 'required|string|max:255',
        ]);

        $unit->update([
            'organ_id' => $request->organ_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.units.index')->with('success', 'واحد با موفقیت بروزرسانی شد.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $unit->delete();
        return redirect()->route('admin.units.index')->with('success', 'واحد با موفقیت حذف شد.');
    }
}
