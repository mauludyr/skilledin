<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\EmploymentInterface;
use Illuminate\Http\Request;

class EmploymentController extends Controller
{
    protected $employmentInterface;

    public function __construct(EmploymentInterface $employmentInterface)
    {
        $this->employmentInterface = $employmentInterface;
    }

    public function store(Request $request)
    {
        return $this->employmentInterface->saveEmployment($request);
    }

    public function update(Request $request, $id)
    {
        return $this->employmentInterface->updateEmployment($request, $id);
    }

}
