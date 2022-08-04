<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface CustomSettingInterface
{
    public function getAllCustomSetting();
    public function findCustomSettingById($gradeId);
    public function saveCustomSetting(Request $request);
    public function updateCustomSetting(Request $request, $id);
    public function deleteCustomSetting($gradeId);
}
