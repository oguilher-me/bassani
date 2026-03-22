<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function showForgotPasswordForm()
    {
        return view('content.authentications.auth-forgot-password-basic');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email-username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $credentials['email-username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // Redirect to dashboard or intended page
        }

        return back()->withErrors([
            'email-username' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email-username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
