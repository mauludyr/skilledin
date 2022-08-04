<?php

namespace App\Http\Controllers;

use App\Mail\EmailActivation;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function getEmailActivation()
    {
        return new EmailActivation();
    }
}
