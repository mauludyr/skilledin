<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function login(Request $request)
    {
        return $this->authInterface->userLogin($request);
    }

    public function logout()
    {
        return $this->authInterface->userLogout();
    }

    public function verify($token)
    {
        return $this->authInterface->userVerify($token);
    }

    public function forgotPassword(Request $request)
    {
        return $this->authInterface->userForgotPassword($request);
    }

    public function resetPassword(Request $request)
    {
        return $this->authInterface->userResetPassword($request);
    }
}
