<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AuthInterface
{
    public function userLogin(Request $request);
    public function userVerify($token);
    public function userForgotPassword(Request $request);
    public function userResetPassword(Request $request);
    public function userLogout();
}
