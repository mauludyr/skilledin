<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\RoleInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleInterface;

    public function __construct(RoleInterface $roleInterface)
    {
        $this->roleInterface = $roleInterface;
    }

    public function showAll() {
        return $this->roleInterface->getAllRole();
    }

    public function showById($id)
    {
        return $this->roleInterface->findRoleById($id);
    }

    public function store(Request $request)
    {
        return $this->roleInterface->saveRole($request);
    }

    public function update(Request $request, $id)
    {
        return $this->roleInterface->updateRole($request, $id);
    }

    //Sync Store Role with Permission request data
    public function syncStore(Request $request)
    {
        return $this->roleInterface->saveAndSyncRolePermission($request);
    }

    public function syncUpdate(Request $request, $id)
    {
        return $this->roleInterface->updateAndSyncRolePermission($request, $id);
    }

}
