<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ProfileInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $profileInterface;

    public function __construct(ProfileInterface $interfaces)
    {
        $this->profileInterface = $interfaces;
    }

    public function store(Request $request)
    {
        return $this->profileInterface->saveProfile($request);
    }

    public function update(Request $request, $id)
    {
        return $this->profileInterface->updateProfile($request, $id);
    }

    public function updateUserAvatar(Request $request)
    {
        return $this->profileInterface->updateUserAvatar($request);
    }

    public function showAllSchema()
    {
        return $this->profileInterface->getAllProfileSchema();
    }

    public function showParticular()
    {
        return $this->profileInterface->getParticular();
    }

    public function showParticularLog()
    {
        return $this->profileInterface->getParticularLog();
    }

    public function updateProfileByAuth(Request $request)
    {
        return $this->profileInterface->updateProfileByAuth($request);
    }

    public function change(Request $request)
    {
        return $this->profileInterface->saveParticularChange($request);
    }

    public function changeStatus(Request $request, $id)
    {
        return $this->profileInterface->particularChangeStatus($request, $id);
    }

    public function destroyParticularLog($id)
    {
        return $this->profileInterface->deleteParticularLog($id);
    }
}
