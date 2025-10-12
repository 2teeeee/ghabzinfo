<?php

namespace App\Http\Controllers;

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
        return view('users.create', compact('roles'));
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
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    /**
     * فرم ویرایش کاربر
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
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
        ]);

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
