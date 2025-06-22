<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Default redirect path (not used when `authenticated()` is defined).
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Redirect users after login based on role.
     */
    protected function authenticated($request, $user)
    {
        if ($user->hasRole('speaker')) {
            return redirect()->route('talks.index');
        }

        if ($user->hasRole('reviewer')) {
            return redirect()->route('reviews.index'); // You must define this route.
        }

        return redirect('/home');
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}