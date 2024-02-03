<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (!empty(Auth::check())) {
            if (Auth::user()->user_type == 1) {
                return redirect()->intended('admin/dashboard');
            } else if (Auth::user()->user_type == 2) {
                return redirect()->intended('teacher/dashboard');
            } else if (Auth::user()->user_type == 3) {
                return redirect()->intended('student/dashboard');
            } else if (Auth::user()->user_type == 4) {
                return redirect()->intended('parent/dashboard');
            }
        }
        return view('auth.login');
    }

    public function authLogin(Request $request)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        $remember = $request->has('remember'); // Check if remember me is checked
        if (Auth::attempt($credentials, $remember)) {
            // Authentication passed
            if (Auth::user()->user_type == 1) {
                return redirect()->intended('admin/dashboard');
            } else if (Auth::user()->user_type == 2) {
                return redirect()->intended('teacher/dashboard');
            } else if (Auth::user()->user_type == 3) {
                return redirect()->intended('student/dashboard');
            } else if (Auth::user()->user_type == 4) {
                return redirect()->intended('parent/dashboard');
            }
        } else {
            // Authentication failed
            return redirect()->back()->with('error', 'Please enter correct email and password');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
