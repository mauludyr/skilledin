<?php

namespace App\Repositories;

use App\Interfaces\ProfileSettingInterface;
use App\Models\ProfileSetting;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileSettingRepository implements ProfileSettingInterface
{
    use ResponseAPI;

    private function queryProfileSetting()
    {
        return ProfileSetting::with([
            "profileFieldSetting" => function($q) {
                $q->select("id","profile_setting_id", "field_name", "field_slug")->orderBy('id','asc');
            }
        ]);
    }

    // Get All Profile Setting
    public function getAllProfileSetting()
    {
        try {
            $results = $this->queryProfileSetting()
                ->orderBy('id', 'asc')
                ->get()
                ->makeHidden(["created_at", "updated_at", "deleted_at"]);

            return $this->successResponse("Get all profile setting", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Profile Setting By Id
    public function findProfileSettingById($id)
    {
        try {
            $data = $this->queryProfileSetting()
                ->find($id)
                ->makeHidden(["created_at", "updated_at", "deleted_at"]);

            if (!$data) {
                return $this->errorResponse("Profile setting not found", 404);
            }

            return $this->successResponse("Success to find Profile setting", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Profile Setting
    public function saveProfileSetting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:profile_settings,name'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();

        $name = ucwords($request->name);
        $slug = Str::slug(Str::lower($name));

        try {
            $data = new ProfileSetting();
            $data->name = $name;
            $data->slug = $slug;
            $data->save();
            DB::commit();

            return $this->successResponse("Profile setting created successfully", $data, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Profile Setting
    public function updateProfileSetting(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:profile_settings,name,' . $id
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = ProfileSetting::find($id);

        if (!$data) {
            return $this->errorResponse("Profile setting not found", 404);
        }


        $name = $request->name ? ucwords($request->name) : $data->name;
        $slug = Str::slug(Str::lower($name));

        DB::beginTransaction();
        try {


            $data->name = $name;
            $data->slug = $slug;
            $data->save();

            DB::commit();

            return $this->successResponse("Profile setting updated successfully", $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    // Delete Profile Setting
    public function deleteProfileSetting($id)
    {
        DB::beginTransaction();
        try {
            $data = ProfileSetting::find($id);

            if (!$data) {
                return $this->errorResponse("Profile setting not found", 404);
            }

            $name = $data->name;
            $data->delete();

            DB::commit();

            return $this->successResponse("Profile setting {$name} deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
