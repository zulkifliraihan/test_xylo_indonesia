<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Services\AuthService\LoginInterface;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $loginInterface;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LoginInterface $loginInterface)
    {
        $this->loginInterface = $loginInterface;
        $this->middleware('guest')->except('logout');
    }

    public function login(LoginRequest $request)
    {
        try {
            // Start : Collecting All Request
            $data = $request->all();
            // End : Collecting All Request

            $loginInterface = $this->loginInterface->login($data);
            // dd($loginInterface);

            if ($loginInterface['statusInterface'] == "failed") {
                return $this->errorvalidator($data['messageInterface']);
            }
            else {
                return $this->successRoute(
                    $loginInterface['dataInterface']->response,
                    $loginInterface['dataInterface']->route,
                    $loginInterface['dataInterface']->message
                );
            }
        }
        catch (\Throwable $th) {
            return $this->errorCode();
        }
    }
}
