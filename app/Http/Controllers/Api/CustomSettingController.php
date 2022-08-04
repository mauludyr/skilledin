<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\CustomSettingInterface;
use Illuminate\Http\Request;

class CustomSettingController extends Controller
{
    protected $customSettingInterface;

    public function __construct(CustomSettingInterface $customSettingInterface)
    {
        $this->customSettingInterface = $customSettingInterface;
    }

    public function showAll() {
        return $this->customSettingInterface->getAllCustomSetting();
    }

    public function showById($id)
    {
        return $this->customSettingInterface->findCustomSettingById($id);
    }

    public function store(Request $request)
    {
        return $this->customSettingInterface->saveCustomSetting($request);
    }

    public function update(Request $request, $id)
    {
        return $this->customSettingInterface->updateCustomSetting($request, $id);
    }

    public function destroy($id)
    {
        return $this->customSettingInterface->deleteCustomSetting($id);
    }
}
