<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Newsletter;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Mail\WelcomeEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'confirmed'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'password.confirmed' => 'Passwords do not match.',
            'email.confirmed' => 'Email addresses do not match.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'birthdate' => Carbon::createFromFormat('d/m/Y', $data['birthdate'])->format('Y-m-d'),
            'country' => $data['country'],
            'role_id' => 2,
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        // Subscribe user to newsletter
        Newsletter::firstOrCreate(['email' => $user->email]);

        // Trigger the Registered event to send verification email
        // event(new Registered($user));
        
        // Log for debugging
        Log::info('User registered and verification email triggered', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        // Send welcome email
        // Mail::to($user->email)->send(new WelcomeEmail($user));

        return $user;
    }

    /**
     * After registration, ensure the device record and cookie are created
     * so the single-device middleware does not immediately log the user out.
     */
    protected function registered($request, $user)
    {
        // Remove any existing device records just in case
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

        // Store current device, retry once if unique constraint triggers
        try {
            $user->devices()->create([
                'device_id' => $deviceId,
                'device_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'session_id' => Session::getId(),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $deviceId = Str::uuid()->toString();
            $user->devices()->create([
                'device_id' => $deviceId,
                'device_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'session_id' => Session::getId(),
            ]);
        }

        // Persist cookie for future requests (1 year)
        cookie()->queue('device_id', $deviceId, 525600);
    }
}
