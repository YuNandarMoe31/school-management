<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authLogin(Request $request)
    {
        // $remember = !empty($request->remember) ? true : false;
        // if (Auth::attempt([
        //     'email' => $request->email,
        //     'password' => $request->password
        // ], true)) {
        //     return redirect('admin/dashboard');
        // } else {
        //     return redirect()->back()->with('error', 'Please enter correct email and password');
        // };
        // Validate the form data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        $remember = $request->has('remember'); // Check if remember me is checked
        if (Auth::attempt($credentials, $remember)) {
            // Authentication passed
            return redirect()->intended('admin/dashboard');
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
