<?php

namespace App\Http\Controllers\Farmer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function loginPage()
    {
        return view('farmer.Auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:20|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
        ]);

        if (auth()->guard('farmer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('farmer.dashboard');
        }

        return back()->withInput($request->only('email'))->with('error_message', 'Invalid email or password');
    }

    public function logout()
    {
        Auth::guard('farmer')->logout();
        return redirect()->route('farmer.login.page');
    }

}
