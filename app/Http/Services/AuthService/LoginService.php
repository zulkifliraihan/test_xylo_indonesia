<?php


namespace App\Http\Services\AuthService;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService implements LoginInterface {
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login($data) : array
    {
        $user = $this->user->where('email', $data['email'])->first();

        // Start : Validation False
        $dataReturn = [];

        if (is_null($user)) {
            $dataReturn['statusInterface'] = 'failed';
            $dataReturn['messageInterface'] = 'Email not found!';
            return $dataReturn;
        }

        if (Hash::check($data['password'], $user->password) == false) {
            $dataReturn['statusInterface'] = 'failed';
            $dataReturn['messageInterface'] = "Wrong Password! Password doesn't match in our record!";
            return $dataReturn;
        }
        // End : Validation False

        $dataReturn['statusInterface'] = 'success';

        $responseForLogin = (object) [
            'response' => 'login',
            'message' => 'Login Success!'
        ];

        // Start : Login
        Auth::loginUsingId($user->id);

        if ($user->hasRole('staff')) {
            $responseForLogin->route = route('staff.parking.index');
        }
        else {
            // $responseForLogin->route = '/';
            $responseForLogin->route = route('admin.parking.index');
        }


        $dataReturn['dataInterface'] = $responseForLogin;

        return $dataReturn;

    }
}
