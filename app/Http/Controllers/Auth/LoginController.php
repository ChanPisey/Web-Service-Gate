<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Auth;
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
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
	{
		return 'username';
	}

	protected function hasTooManyLoginAttempts(Request $request)
	{
		return $this->limiter()->tooManyAttempts(
			$this->throttleKey($request), 3, 1
		);
	}

	protected function sendLoginResponse(Request $request)
	{

		if ($this->hasTooManyLoginAttempts($request)) {
			$this->fireLockoutEvent($request);

			return $this->sendLockoutResponse($request);
		}

		$request->session()->regenerate();

		$this->clearLoginAttempts($request);
		return $this->authenticated($request, $this->guard()->user())
			?: response()->json(['status'=>1 , 'success'=>'success', 'redirect'=> $this->redirectTo]);
	}

	protected function sendFailedLoginResponse(Request $request)
	{

		return response()->json(['status'=>0 , 'error'=> Lang::get('auth.failed')]);
	}


	protected function sendLockoutResponse(Request $request)
	{
		$seconds = $this->limiter()->availableIn(
			$this->throttleKey($request)
		);

		$message = Lang::get('auth.throttle', ['seconds' => $seconds]);

		return response()->json(['status'=>0 ,'error'=> $message]);
	}

}
