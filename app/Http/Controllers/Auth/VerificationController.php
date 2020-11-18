<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\User;
use App\Mail\Verification;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */


    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('auth.verify');
    }





    public function verify()
    {
        if (!is_null(session('user_id'))) {
            $user = User::where('verified_at', null)->findOrFail(session('user_id'));
            if ($user) {
                $user->verify_token = Str::random(20);
                $user->save();
                Mail::to($user->email)->send(new Verification($user));
                return view('auth.verify', $user);
            }
        } else {
            return redirect()->back();
        }
    }


    public function verifyToken ($token) {
        $user = User::where('verified_at', null)->where('verify_token', $token)->firstOrFail();
        if ($user) {
            $user->verify_token = null;
            $user->verified_at = now();
            $user->save();
            if (session('user_id') == $user->_id) {
                Auth::login($user);
                session()->pull('user_id');
            }
            return redirect()->route('monitoring');
        }
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }




    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
}
