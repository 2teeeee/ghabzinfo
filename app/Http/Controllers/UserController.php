<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * نمایش لیست کاربران
     */
    public function index(): View
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * فرم ایجاد کاربر جدید
     */
    public function create(): View
    {
        $roles = Role::all();

        $cities = City::orderBy('name')->get(['id', 'name']);

        return view('users.create', compact('roles', 'cities'));
    }

    /**
     * ذخیره کاربر جدید
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'mobile' => 'required|string|max:11|unique:users,mobile',
            'password' => 'required|string|min:6|confirmed',
            'bill_limit' => 'nullable|integer|min:1',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'city_id' => 'nullable|exists:cities,id',
            'organ_id' => 'nullable|exists:organs,id',
            'unit_id' => 'nullable|exists:units,id',
            'center_id' => 'nullable|exists:centers,id',
        ]);

        /** @var \Illuminate\Support\Collection $roles */
        $roles = \App\Models\Role::whereIn('id', $data['roles'] ?? [])->pluck('name');

        // بررسی اجبار وابسته به نقش
        if ($roles->contains('city')) {
            $request->validate(['city_id' => 'required|exists:cities,id']);
        }
        if ($roles->contains('organ')) {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'organ_id' => 'required|exists:organs,id',
            ]);
        }
        if ($roles->contains('unit')) {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'organ_id' => 'required|exists:organs,id',
                'unit_id' => 'required|exists:units,id',
            ]);
        }
        if ($roles->contains('center')) {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'organ_id' => 'required|exists:organs,id',
                'unit_id' => 'required|exists:units,id',
                'center_id' => 'required|exists:centers,id',
            ]);
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    /**
     * فرم ویرایش کاربر
     */
    public function edit(User $user): View
    {
        $roles = Role::all();

        $cities = City::orderBy('name')->get(['id', 'name']);

        return view('users.edit', compact('user', 'roles', 'cities'));
    }

    /**
     * بروزرسانی اطلاعات کاربر
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:11|unique:users,mobile,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'bill_limit' => 'nullable|integer|min:1',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'city_id' => 'nullable|exists:cities,id',
            'organ_id' => 'nullable|exists:organs,id',
            'unit_id' => 'nullable|exists:units,id',
            'center_id' => 'nullable|exists:centers,id',
        ]);

        /** @var \Illuminate\Support\Collection $roles */
        $roles = Role::whereIn('id', $data['roles'] ?? [])->pluck('name');

        // بررسی اجبار وابسته به نقش
        if ($roles->contains('city')) {
            $request->validate(['city_id' => 'required|exists:cities,id']);
        }
        if ($roles->contains('organ')) {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'organ_id' => 'required|exists:organs,id',
            ]);
        }
        if ($roles->contains('unit')) {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'organ_id' => 'required|exists:organs,id',
                'unit_id' => 'required|exists:units,id',
            ]);
        }
        if ($roles->contains('center')) {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'organ_id' => 'required|exists:organs,id',
                'unit_id' => 'required|exists:units,id',
                'center_id' => 'required|exists:centers,id',
            ]);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        $user->roles()->sync($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ویرایش شد.');
    }

    /**
     * حذف کاربر
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'کاربر حذف شد.');
    }
}
