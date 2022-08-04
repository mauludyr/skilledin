<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface PermissionInterface
{
    public function getAllPermission();
    public function findPermissionById($id);
    public function savePermission(Request $request);
    public function updatePermission(Request $request, $id);
}
