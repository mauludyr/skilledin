<?php

namespace App\Repositories;

use App\Interfaces\PermissionInterface;
use Spatie\Permission\Models\Permission;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PermissionRepository implements PermissionInterface
{
    use ResponseAPI;

    // Get All Permission
    public function getAllPermission()
    {
        try {
            $results = Permission::get()->makeHidden(["created_at", "updated_at"]);
            return $this->successResponse("Get all permissions", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Permission By Id
    public function findPermissionById($id)
    {
        try {
            $data = Permission::find($id)->setHidden(["created_at", "updated_at"]);

            if (!$data) {
                return $this->errorResponse("Permission not found", 404);
            }

            return $this->successResponse("Success to find permission", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Permission
    public function savePermission(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:permissions,name'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $data = Permission::create(['name' => Str::lower($request->name)]);
            return $this->successResponse("Permission created successfully", $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Permission
    public function updatePermission(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:permissions,name,'.$id
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {

            $data = Permission::find($id);

            if (!$data) {
                return $this->errorResponse("Permission not found", 404);
            }

            $data->name = Str::lower($request->name);
            $data->save();
            return $this->successResponse("Permission updated successfully", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

}
