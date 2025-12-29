<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Remove all devices before logout
        if ($request->user()) {
            $request->user()->devices()->delete();
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }



    protected function sendFailedLoginResponse(Request $request)
    {
        $user = \App\Models\User::where($this->username(), $request->{$this->username()})->first();

        if (!$user) {
            // Email doesn't exist
            throw \Illuminate\Validation\ValidationException::withMessages([
                $this->username() => ['The email you entered does not exist.'],
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            // Email exists but password wrong
            throw \Illuminate\Validation\ValidationException::withMessages([
                'password' => ['The password you entered is incorrect.'],
            ]);
        }

        // fallback default
        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => ['Login failed.'],
        ]);
    }



    protected function authenticated(Request $request, $user)
    {
        // Remove all existing devices on new login to ensure only one active session
        $user->devices()->delete();

        // Use cookie only if not already claimed by another user; otherwise, generate a fresh UUID
        $incoming = $request->cookie('device_id');
        $conflictExists = $incoming
            ? \App\Models\UserDevice::where('device_id', $incoming)
                ->where('user_id', '!=', $user->id)
                ->exists()
            : false;

        $deviceId = $conflictExists || empty($incoming)
            ? Str::uuid()->toString()
            : $incoming;

        // Create new device entry for current login, retry once on rare race duplicate
        try {
            $user->devices()->create([
                'device_id' => $deviceId,
                'device_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'session_id' => Session::getId(),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // If duplicate device_id slipped through, regenerate and insert
            $deviceId = Str::uuid()->toString();
            $user->devices()->create([
                'device_id' => $deviceId,
                'device_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'session_id' => Session::getId(),
            ]);
        }

        // Ensure cookie is set
        cookie()->queue('device_id', $deviceId, 525600); // 1 year
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
