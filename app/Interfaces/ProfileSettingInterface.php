<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface ProfileSettingInterface
{
    public function getAllProfileSetting();
    public function findProfileSettingById($id);
    public function saveProfileSetting(Request $request);
    public function updateProfileSetting(Request $request, $id);
    public function deleteProfileSetting($id);
}
