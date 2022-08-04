<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface EmploymentInterface
{
    public function saveEmployment(Request $request);
    public function updateEmployment(Request $request, $id);
}
