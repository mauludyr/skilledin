<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\JobPositionInterface;
use Illuminate\Http\Request;

class JobPositionController extends Controller
{
    protected $jobInterface;

    public function __construct(JobPositionInterface $jobInterface)
    {
        $this->jobInterface = $jobInterface;
    }


    public function showAll() {
        return $this->jobInterface->getAllJobPosition();
    }

    public function showById($id)
    {
        return $this->jobInterface->findJobPositionById($id);
    }


    public function showBySlug($slug)
    {
        return $this->jobInterface->findJobPositionBySlug($slug);
    }

    public function store(Request $request)
    {
        return $this->jobInterface->saveJobPosition($request);
    }

    public function update(Request $request, $id)
    {
        return $this->jobInterface->updateJobPosition($request, $id);
    }

    public function destroy($id)
    {
        return $this->jobInterface->deleteJobPosition($id);
    }
}
