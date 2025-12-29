<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDeviceSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            $deviceId = $request->cookie('device_id');

            // If no device_id cookie exists, logout the user
            if (!$deviceId) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with('error', 'Your session has expired. Please login again.');
            }

            // Check if this device_id exists for this user
            $deviceExists = $user->devices()
                ->where('device_id', $deviceId)
                ->exists();

            // If device doesn't exist in database, logout the user
            if (!$deviceExists) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with('error', 'You have been logged out because you logged in from another device.');
            }
        }

        return $next($request);
    }
}
