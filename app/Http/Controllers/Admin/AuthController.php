<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (request()->session()->get('is_admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $adminEmail    = (string) Config::get('services.admin.email');
        $adminPassword = (string) Config::get('services.admin.password');

        $emailOk = hash_equals(strtolower($adminEmail), strtolower(trim($credentials['email'])));
        $passOk  = hash_equals($adminPassword, $credentials['password']);

        if (! $emailOk || ! $passOk || $adminEmail === '' || $adminPassword === '') {
            throw ValidationException::withMessages([
                'email' => 'Those credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put('is_admin', true);
        $request->session()->put('admin_email', $adminEmail);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['is_admin', 'admin_email']);
        $request->session()->regenerate();

        return redirect()->route('admin.login');
    }
}
