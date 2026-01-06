<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomerAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login.page')->with('error_message', 'Invalid email or password');
        }
        if (Auth::guard('customer')->user()->status === 'inactive') {
            Auth::guard('customer')->logout();
            return redirect()->route('customer.login.page')->with('error_message', 'Your account is inactive.');
        }
        return $next($request);
    }
}
