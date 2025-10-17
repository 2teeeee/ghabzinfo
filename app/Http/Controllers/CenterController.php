<?php
namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\City;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    public function index(): View
    {
        $centers = Center::with('unit.organ.city')->paginate(15);
        return view('admin.centers.index', compact('centers'));
    }

    public function create(): View
    {
        $cities = City::all();
        return view('admin.centers.create', compact('cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'organ_id' => 'required|exists:organs,id',
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
        ]);

        Center::create([
            'unit_id' => $request->unit_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.centers.index')->with('success', 'مرکز با موفقیت ثبت شد.');
    }

    public function edit(Center $center): View
    {
        $cities = City::all();
        return view('admin.centers.edit', compact('center', 'cities'));
    }

    public function update(Request $request, Center $center): RedirectResponse
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'organ_id' => 'required|exists:organs,id',
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
        ]);

        $center->update([
            'unit_id' => $request->unit_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.centers.index')->with('success', 'مرکز با موفقیت بروزرسانی شد.');
    }

    public function destroy(Center $center): RedirectResponse
    {
        $center->delete();
        return redirect()->route('admin.centers.index')->with('success', 'مرکز با موفقیت حذف شد.');
    }
}
