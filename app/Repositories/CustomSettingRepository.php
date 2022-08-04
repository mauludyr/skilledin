<?php

namespace App\Repositories;

use App\Interfaces\CustomSettingInterface;
use App\Models\CustomFieldSetting;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomSettingRepository implements CustomSettingInterface
{
    use ResponseAPI;

    // Get All Custom Setting
    public function getAllCustomSetting()
    {
        try {
            $results = CustomFieldSetting::get();
            return $this->successResponse("Get all custom field setting", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Custom Setting By Id
    public function findCustomSettingById($id)
    {
        try {
            $data = CustomFieldSetting::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field setting not found", 404);
            }

            return $this->successResponse("Success to find custom field setting", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Custom Setting
    public function saveCustomSetting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:custom_field_settings,name'
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
            $data = new CustomFieldSetting();
            $data->name = Str::lower($request->name);

            if($request->has('description'))
            {
                $data->description = $request->description;
            }


            $data->save();
            DB::commit();

            return $this->successResponse("Custom field setting created successfully", $data, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Custom Setting
    public function updateCustomSetting(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:custom_field_settings,name,' . $id
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

            $data = CustomFieldSetting::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field setting not found", 404);
            }

            $data->name = Str::lower($request->name);

            if($request->has('description'))
            {
                $data->description = $request->description;
            }

            $data->save();

            DB::commit();

            return $this->successResponse("Custom field setting updated successfully", $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    // Delete Custom Setting
    public function deleteCustomSetting($id)
    {
        DB::beginTransaction();
        try {
            $data = CustomFieldSetting::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field setting not found", 404);
            }

            $name = $data->name;
            $data->delete();

            DB::commit();

            return $this->successResponse("Custom field setting {$name} deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
