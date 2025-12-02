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
        // $deviceId = $request->cookie('device_id') ?? Str::uuid()->toString();

        // $existingDevice = $user->devices()->where('device_id', $deviceId)->first();

        // if (!$existingDevice) {
        //     if ($user->devices()->count() >= 1) {
        //         auth()->logout();
        //         Session::invalidate();
        //         Session::regenerateToken();

        //         return redirect()->route('login')->withErrors([
        //             'email' => 'You are already logged in on 1 devices.',
        //         ]);
        //     }

        //     $user->devices()->create([
        //         'device_id' => $deviceId,
        //         'device_agent' => $request->userAgent(),
        //         'ip_address' => $request->ip(),
        //         'session_id' => Session::getId(),
        //     ]);
        // } else {
        //     $existingDevice->update([
        //         'session_id' => Session::getId(),
        //         'ip_address' => $request->ip(),
        //         'device_agent' => $request->userAgent()
        //     ]);
        // }

        // // Ensure cookie is set
        // cookie()->queue('device_id', $deviceId, 525600); // 1 year
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
