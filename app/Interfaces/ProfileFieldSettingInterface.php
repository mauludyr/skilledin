<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface ProfileFieldSettingInterface
{
    // Get All Profile Field Setting
    public function getAllProfileFieldSetting();

    // Find Profile Field Setting By ID
    public function findProfileFieldSettingById($id);


    // Save Profile Field Setting
    public function saveProfileFieldSetting(Request $request);


    // Update Profile Field Setting
    public function updateProfileFieldSetting(Request $request, $id);



    // Delete Profile Field Setting
    public function deleteProfileFieldSetting($id);

    // Upgrade Profile Setting
    public function upgradeSetting(Request $request);
}
