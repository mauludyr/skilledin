<?php

namespace App\Repositories;

use App\Interfaces\CustomTypeInterface;
use App\Models\CustomFieldType;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomTypeRepository implements CustomTypeInterface
{
    use ResponseAPI;

    // Get All Custom Type
    public function getAllCustomType()
    {
        try {
            $results = CustomFieldType::get();
            return $this->successResponse("Get all custom field type", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Custom Type By Id
    public function findCustomTypeById($id)
    {
        try {
            $data = CustomFieldType::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field type not found", 404);
            }

            return $this->successResponse("Success to find custom field type", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Custom Type
    public function saveCustomType(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:custom_field_types,name'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();

        try {
            $data = new CustomFieldType();
            $data->name = Str::lower($request->name);
            $data->save();
            DB::commit();

            return $this->successResponse("Custom field type created successfully", $data, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Custom Type
    public function updateCustomType(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:custom_field_types,name,' . $id
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {

            $data = CustomFieldType::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field type not found", 404);
            }

            $data->name = Str::lower($request->name);
            $data->save();

            DB::commit();

            return $this->successResponse("Custom field type updated successfully", $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    // Delete Custom Type
    public function deleteCustomType($id)
    {
        DB::beginTransaction();
        try {
            $data = CustomFieldType::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field type not found", 404);
            }

            $name = $data->name;
            $data->delete();

            DB::commit();

            return $this->successResponse("Custom field type {$name} deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
