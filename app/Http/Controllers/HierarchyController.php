<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Organ;
use App\Models\Unit;
use App\Models\Center;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HierarchyController extends Controller
{
    protected int $cacheMinutes = 60; // مدت کش (۱ ساعت)

    public function organs(Request $request): JsonResponse
    {
        $request->validate(['city_id' => 'required|integer']);
        $cityId = $request->city_id;
        $user = Auth::user();

        // کنترل دسترسی
        if ($user->hasRole('city') && $user->city_id != $cityId) {
            return response()->json(['success' => false, 'message' => 'دسترسی غیرمجاز.'], 403);
        }

        $cacheKey = "organs_city_{$cityId}";
        $organs = Cache::remember($cacheKey, now()->addMinutes($this->cacheMinutes), function () use ($cityId) {
            return Organ::query()
                ->select('id', 'name')
                ->where('city_id', $cityId)
                ->orderBy('name')
                ->get();
        });

        return response()->json(['success' => true, 'data' => $organs]);
    }

    public function units(Request $request): JsonResponse
    {
        $request->validate(['organ_id' => 'required|integer']);
        $organId = $request->organ_id;
        $user = Auth::user();

        if ($user->hasRole('organ') && $user->organ_id != $organId) {
            return response()->json(['success' => false, 'message' => 'دسترسی غیرمجاز.'], 403);
        }

        $cacheKey = "units_organ_{$organId}";
        $units = Cache::remember($cacheKey, now()->addMinutes($this->cacheMinutes), function () use ($organId) {
            return Unit::query()
                ->select('id', 'name')
                ->where('organ_id', $organId)
                ->orderBy('name')
                ->get();
        });

        return response()->json(['success' => true, 'data' => $units]);
    }

    public function centers(Request $request): JsonResponse
    {
        $request->validate(['unit_id' => 'required|integer']);
        $unitId = $request->unit_id;
        $user = Auth::user();

        if ($user->hasRole('vahed') && $user->unit_id != $unitId) {
            return response()->json(['success' => false, 'message' => 'دسترسی غیرمجاز.'], 403);
        }

        $cacheKey = "centers_unit_{$unitId}";
        $centers = Cache::remember($cacheKey, now()->addMinutes($this->cacheMinutes), function () use ($unitId) {
            return Center::query()
                ->select('id', 'name')
                ->where('unit_id', $unitId)
                ->orderBy('name')
                ->get();
        });

        return response()->json(['success' => true, 'data' => $centers]);
    }
}
