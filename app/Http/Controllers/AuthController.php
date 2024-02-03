<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    public function postForgotPassword(Request $request)
    {
        $user = User::getEmailSingle($request->email);

        if (!empty($user)) {
            $user->remember_token = Str::random(30);
            $user->save();
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            return redirect()->back()->with('success', 'Please check your email');
        } else {
            return redirect()->back()->with('error', 'Email not found in the system');
        }
    }

    public function reset($remember_token)
    {
        $user = User::getTokenSingle($remember_token);

        if(!empty($user))
        {
            $data['user'] = $user;
            return view('auth.reset', $data);
        } else {
            abort(404);
        }
    }

    public function postReset(Request $request, $token)
    {
        if($request->password == $request->cpassword)
        {
            $user = User::getTokenSingle($token);
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(30);
            $user->save();

            return redirect('/')->with('success', "Password successfully created");
        } else {
            return redirect()->back()->with('error', 'Password and confirm password does not match');
        }
    }

}
