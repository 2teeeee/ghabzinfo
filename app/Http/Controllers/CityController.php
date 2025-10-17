<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(): View
    {
        $cities = City::latest()->paginate(10);
        return view('admin.cities.index', compact('cities'));
    }

    public function create(): View
    {
        return view('admin.cities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:cities,name'],
        ]);

        City::create($validated);

        return redirect()->route('admin.cities.index')->with('success', 'شهر با موفقیت ایجاد شد.');
    }

    public function edit(City $city): View
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', "unique:cities,name,{$city->id}"],
        ]);

        $city->update($validated);

        return redirect()->route('admin.cities.index')->with('success', 'شهر با موفقیت ویرایش شد.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'شهر حذف شد.');
    }

}
