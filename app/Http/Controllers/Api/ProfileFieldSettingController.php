<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ProfileFieldSettingInterface;
use Illuminate\Http\Request;

class ProfileFieldSettingController extends Controller
{
    protected $profileFieldSettingInterface;

    public function __construct(ProfileFieldSettingInterface $profileFieldSettingInterface)
    {
        $this->profileFieldSettingInterface = $profileFieldSettingInterface;
    }

    public function showAll() {
        return $this->profileFieldSettingInterface->getAllProfileFieldSetting();
    }

    public function showById($id)
    {
        return $this->profileFieldSettingInterface->findProfileFieldSettingById($id);
    }


    public function store(Request $request)
    {
        return $this->profileFieldSettingInterface->saveProfileFieldSetting($request);
    }

    public function update(Request $request, $id)
    {
        return $this->profileFieldSettingInterface->updateProfileFieldSetting($request, $id);
    }

    public function destroy($id)
    {
        return $this->profileFieldSettingInterface->deleteProfileFieldSetting($id);
    }

    public function upgrade(Request $request)
    {
        return $this->profileFieldSettingInterface->upgradeSetting($request);
    }
}
