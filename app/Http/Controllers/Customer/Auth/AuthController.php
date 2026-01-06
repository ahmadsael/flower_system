<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\CustomerLoginRequest;
use App\Http\Requests\Customer\Auth\CustomerRegisterRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage(): RedirectResponse
    {
        return redirect()->route('home', ['section' => 'login']);
    }

    public function registerPage(): RedirectResponse
    {
        return redirect()->route('home', ['section' => 'register']);
    }

    public function login(CustomerLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('home')->with('success_message', 'Welcome back to Bloom & Blossom!');
        }

        return redirect()
            ->route('home', ['section' => 'login'])
            ->withInput($request->only('email'))
            ->with('error_message', 'Invalid email or password');
    }

    public function register(CustomerRegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $customer = Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'gender' => $data['gender'] ?? 'male',
            'birthday' => $data['birthday'] ?? null,
            'age' => $data['age'] ?? null,
        ]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success_message', 'Welcome to Bloom & Blossom! Your account is ready.');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('customer')->logout();

        return redirect()->route('home')->with('success_message', 'Signed out successfully.');
    }
}
