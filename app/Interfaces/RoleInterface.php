<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface RoleInterface
{
    public function getAllRole();
    public function findRoleById($id);
    public function saveRole(Request $request);
    public function updateRole(Request $request, $id);

    public function saveAndSyncRolePermission(Request $request);
    public function updateAndSyncRolePermission(Request $request, $id);
}
