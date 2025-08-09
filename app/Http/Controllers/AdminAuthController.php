<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Custom admin authentication
        if ($credentials['username'] === 'ADMIN' && $credentials['password'] === 'ADMIN') {
            session(['admin_authenticated' => true]);
            return redirect()->intended(route('admin.tickets.index'));
        }

        return back()->withErrors([
            'username' => 'Invalid credentials.',
        ]);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('admin.login');
    }
}
