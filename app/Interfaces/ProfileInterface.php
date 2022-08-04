<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ProfileInterface
{
    public function saveProfile(Request $request);
    public function saveParticularChange(Request $request);
    public function particularChangeStatus(Request $request, $id);
    public function updateProfile(Request $request, $id);
    public function updateUserAvatar(Request $request);
    public function getAllProfileSchema();
    public function getParticular();
    public function getParticularLog();
    public function updateProfileByAuth(Request $request);
    public function deleteParticularLog($id);
}
