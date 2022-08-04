<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ProfileSettingInterface;
use Illuminate\Http\Request;

class ProfileSettingController extends Controller
{
    protected $profileSettingInterface;

    public function __construct(ProfileSettingInterface $profileSettingInterface)
    {
        $this->profileSettingInterface = $profileSettingInterface;
    }

    public function showAll() {
        return $this->profileSettingInterface->getAllProfileSetting();
    }

    public function showById($id)
    {
        return $this->profileSettingInterface->findProfileSettingById($id);
    }

    public function store(Request $request)
    {
        return $this->profileSettingInterface->saveProfileSetting($request);
    }

    public function update(Request $request, $id)
    {
        return $this->profileSettingInterface->updateProfileSetting($request, $id);
    }

    public function destroy($id)
    {
        return $this->profileSettingInterface->deleteProfileSetting($id);
    }
}
