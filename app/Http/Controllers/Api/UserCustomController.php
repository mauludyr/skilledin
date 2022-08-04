<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\UserCustomInterface;
use Illuminate\Http\Request;

class UserCustomController extends Controller
{
    protected $customInterface;

    public function __construct(UserCustomInterface $interfaces)
    {
        $this->customInterface = $interfaces;
    }

    public function storeUserCustomField(Request $request)
    {
        return $this->customInterface->saveUserCustomField($request);
    }

    public function updateUserCustomField(Request $request)
    {
        return $this->customInterface->updateUserCustomField($request);
    }


}
