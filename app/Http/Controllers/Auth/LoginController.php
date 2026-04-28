<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /**
     * Show login page
     */
    public function showLoginForm()
{
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
}
    /**
     * Handle login request
     */
    public function login(LoginRequest $request)
    {
        // ================= CAPTCHA VALIDATION =================
        $captchaResponse = $request->input('g-recaptcha-response');

        if (!$captchaResponse) {
            return back()->withErrors([
                'captcha' => 'Please verify that you are not a robot.',
            ]);
        }

        $verifyResponse = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => env('RECAPTCHA_SECRET_KEY'),
                'response' => $captchaResponse,
                'remoteip' => $request->ip(),
            ]
        );

        $captchaResult = $verifyResponse->json();

        if (!($captchaResult['success'] ?? false)) {
            return back()->withErrors([
                'captcha' => 'Captcha verification failed. Please try again.',
            ]);
        }

        // ================= LOGIN ATTEMPT =================
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}