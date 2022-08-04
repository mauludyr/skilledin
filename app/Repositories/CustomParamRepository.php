<?php

namespace App\Repositories;

use App\Interfaces\CustomParamInterface;
use App\Models\CustomFieldParams;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomParamRepository implements CustomParamInterface
{
    use ResponseAPI;

    // Get All Custom Param
    public function getAllCustomParam()
    {
        try {
            $results = CustomFieldParams::get();
            return $this->successResponse("Get all custom field param", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Find Custom Param By Id
    public function findCustomParamById($id)
    {
        try {
            $data = CustomFieldParams::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field param not found", 404);
            }

            return $this->successResponse("Success to find custom field param", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Find Custom Param By Slug
    public function findCustomParamBySlug($slug)
    {
        try {
            $data = CustomFieldParams::where('slug', $slug)->first();

            if (!$data) {
                return $this->errorResponse("Custom field param not found", 404);
            }

            return $this->successResponse("Success to find custom field param", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Custom Param
    public function saveCustomParam(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:custom_field_params,name'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $name = ucwords(Str::lower($request->name));
            $data = CustomFieldParams::create([
                "name" => $name,
                "slug" => Str::slug(Str::lower(trim($name)), '_')
            ]);

            return $this->successResponse("Custom field param created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Update Custom Param
    public function updateCustomParam(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:custom_field_params,name,' . $id
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
            $data = CustomFieldParams::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field params not found", 404);
            }

            $data->name = ucwords(Str::lower($request->name));
            $data->slug = Str::slug(Str::lower(trim($request->name)), "_");
            $data->save();

            DB::commit();

            return $this->successResponse("Custom field setting updated successfully", $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Delete Custom Param
    public function deleteCustomParam($id)
    {
        DB::beginTransaction();
        try {
            $data = CustomFieldParams::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field params not found", 404);
            }

            $name = $data->name;
            $data->delete();

            DB::commit();

            return $this->successResponse("Custom field params {$name} deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
