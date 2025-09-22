<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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

    protected function authenticated(Request $request, $user)
    {
        $deviceAgent = $request->header('User-Agent');
        $deviceName = $request->server('HTTP_USER_AGENT'); // or use custom device naming logic
        $sessionId = Session::getId();

        // Check if this device already registered
        $existingDevice = $user->devices()
            ->where('device_agent', $deviceAgent)
            ->first();

        if ($existingDevice) {
            // Update session ID
            $existingDevice->update(['session_id' => $sessionId]);
        } else {
            // Count how many devices are registered
            if ($user->devices()->count() >= 2) {
                auth()->logout();
                Session::invalidate();
                Session::regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'You have reached the maximum number of allowed devices (2).',
                ]);
            }

            // Register new device
            $user->devices()->create([
                'device_name' => $deviceName,
                'device_agent' => $deviceAgent,
                'session_id' => $sessionId,
            ]);
        }
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
