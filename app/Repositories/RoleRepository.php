<?php

namespace App\Repositories;

use App\Interfaces\RoleInterface;
use Spatie\Permission\Models\Role;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class RoleRepository implements RoleInterface
{
    use ResponseAPI;


    // Get All Role
    public function getAllRole()
    {
        try {
            $results = Role::get()->makeHidden(["created_at", "updated_at"]);
            return $this->successResponse("Get all Roles", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Role By Id
    public function findRoleById($id)
    {
        try {
            $data = Role::find($id)->setHidden(["created_at", "updated_at"]);

            if (!$data) {
                return $this->errorResponse("Role not found", 404);
            }

            return $this->successResponse("Success to find Role", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Role
    public function saveRole(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:roles,name'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $data = Role::create(['name' => Str::lower($request->name)]);
            return $this->successResponse("Role created successfully", $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Role
    public function updateRole(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:roles,name,'.$id
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {

            $data = Role::find($id);

            if (!$data) {
                return $this->errorResponse("Role not found", 404);
            }

            $data->name = Str::lower($request->name);
            $data->save();
            return $this->successResponse("Role updated successfully", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    //Save and Synchronize Role Permission
    public function saveAndSyncRolePermission(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:roles,name',
                'permissions' => 'required|array'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.',
                'permissions.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $permissions = Permission::whereIn('id', $request->permissions)->get();

        try {
            $data = Role::create(['name' => Str::lower($request->name)]);

            if(!$data) {
                return $this->errorResponse('Role failed to create', 400);
            }

            $data->givePermissionTo($permissions);

            return $this->successResponse("Role created successfully", $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update and Synchronize Role Permission
    public function updateAndSyncRolePermission(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:roles,name,'.$id,
                'permissions' => 'required|array'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.',
                'permissions.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $permissions = Permission::whereIn('id', $request->permissions)->get();

        try {

            $data = Role::find($id);

            if (!$data) {
                return $this->errorResponse("Role not found", 404);
            }

            $data->name = Str::lower($request->name);
            $data->save();
            $data->syncPermissions($permissions);

            return $this->successResponse("Role updated successfully", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

}
