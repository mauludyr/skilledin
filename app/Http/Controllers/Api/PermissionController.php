<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\PermissionInterface;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionInterface;

    public function __construct(PermissionInterface $permissionInterface)
    {
        $this->permissionInterface = $permissionInterface;
    }

    public function showAll() {
        return $this->permissionInterface->getAllPermission();
    }

    public function showById($id)
    {
        return $this->permissionInterface->findPermissionById($id);
    }

    public function store(Request $request)
    {
        return $this->permissionInterface->savePermission($request);
    }

    public function update(Request $request, $id)
    {
        return $this->permissionInterface->updatePermission($request, $id);
    }

}
