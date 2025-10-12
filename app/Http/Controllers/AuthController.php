<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'mobile' => ['required', 'string', 'max:11', 'unique:users,mobile'],
            'email' => ['nullable', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username ?? $request->mobile,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'bill_limit' => 10,
            ]);

            if (method_exists($user, 'roles')) {
                $user->roles()->attach(
                    \App\Models\Role::where('name', 'user')->value('id')
                );
            }

            event(new Registered($user));

            DB::commit();

            Auth::login($user);

            return redirect()->route('main.index')->with('success', 'ثبت‌نام با موفقیت انجام شد.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'خطا در ثبت‌نام: ' . $e->getMessage()]);
        }
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('main.index', absolute: false));
        }

        return back()->withErrors([
            'mobile' => 'کاربری با این مشخصات وجود ندارد.',
        ])->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
