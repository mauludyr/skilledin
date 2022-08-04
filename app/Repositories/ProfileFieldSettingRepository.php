<?php

namespace App\Repositories;

use App\Interfaces\ProfileFieldSettingInterface;
use App\Models\CombineFieldSetting;
use App\Models\ProfileFieldSetting;
use App\Models\ProfileSetting;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileFieldSettingRepository implements ProfileFieldSettingInterface
{
    use ResponseAPI;

    public function queryProfileFieldSetting()
    {
        return ProfileFieldSetting::with([
            'profileSetting' => function($q) {
                $q->select("id", "name", "slug");
            },
        ]);
    }


    // Get All Profile Field Setting
    public function getAllProfileFieldSetting()
    {
        try {
            $results = $this->queryProfileFieldSetting()
                ->get()
                ->makeHidden(["created_at", "updated_at", "deleted_at"]);

            return $this->successResponse("Get all profile field setting", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Find Profile Field Setting By ID
    public function findProfileFieldSettingById($id)
    {
        try {
            $data = $this->queryProfileFieldSetting()->find($id);

            if (!$data) {
                return $this->errorResponse("Profile field setting not found", 404);
            }

            return $this->successResponse("Success to find profile field setting", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Save Profile Field Setting
    public function saveProfileFieldSetting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'profile_setting_id' => 'required',
                'field_name' => 'required|unique:profile_field_settings,field_name',
            ],
            [
                'profile_setting_id.required' => 'The :attribute field can not be blank value.',
                'field_name.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $name = ucwords($request->field_name);
        $slug = Str::slug(Str::lower($name));

        $fieldSetting = ProfileFieldSetting::where('field_slug', $slug)->where('profile_setting_id', $request->profile_setting_id)->first();

        if (!$fieldSetting) {
            return $this->errorResponse("Profile field setting has already existed", 400);
        }

        
        try {
            $data = ProfileFieldSetting::updateOrCreate([
                'profile_setting_id' => $request->profile_setting_id,
                'field_name' => $name,
                'field_slug' => $slug
            ]);


            return $this->successResponse("Profile field setting created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }


    // Update Profile Field Setting
    public function updateProfileFieldSetting(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'field_name' => 'required|unique:profile_field_settings,field_name,'. $id,
            ],
            [
                'field_name.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }



        $data = ProfileFieldSetting::find($id);

        if (!$data) {
            return $this->errorResponse("Profile field setting not found", 404);
        }

        $name = $request->field_name ? ucwords($request->field_name) : $data->field_name;
        $slug = Str::slug(Str::lower($name));

        try {

            $data->field_name = $name;
            $data->field_slug = $slug;
            $data->save();

            return $this->successResponse("Profile field setting updated successfully", $data);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }


    // Delete Profile Field Setting
    public function deleteProfileFieldSetting($id)
    {
        try {
            $data = ProfileFieldSetting::with("profileSetting")->find($id);
            if (!$data) {
                return $this->errorResponse("Profile Field Setting not found", 404);
            }
            $data->delete();
            return $this->successResponse("Profile Field Setting deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function upgradeSetting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'field_settings' => 'required',
            ],
            [
                'field_name.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        if(count($request->field_settings) <= 0)
        {
            return $this->errorResponse("Field setting must be arrays", 400);
        }

        $count = 0;
        foreach ($request->field_settings as $value) {

            // Is Everyone
            if($value["is_everyone"] == true) {
                $combines = CombineFieldSetting::where('field_setting_id', $value["field_setting_id"])
                    ->where("label_name", "everyone")->first();

                if($combines) {
                    $combines->is_public = $value["is_public"];
                    $combines->save();
                }
                else {
                    CombineFieldSetting::create([
                        "field_setting_id" => $value["field_setting_id"],
                        "label_name" => "everyone",
                        "is_public" => $value["is_public"],
                    ]);
                }
            }

            // Is HR Business Partner
            if($value["is_hr_business"] == true) {
                $combines = CombineFieldSetting::where('field_setting_id', $value["field_setting_id"])
                    ->where("label_name", "hr_business")->first();

                if($combines) {
                    $combines->is_public = $value["is_public"];
                    $combines->save();
                }
                else {
                    CombineFieldSetting::create([
                        "field_setting_id" => $value["field_setting_id"],
                        "label_name" => "hr_business",
                        "is_public" => $value["is_public"],
                    ]);
                }
            }

            // Is Direct Manager
            if($value["is_direct_manager"] == true) {
                $combines = CombineFieldSetting::where('field_setting_id', $value["field_setting_id"])
                    ->where("label_name", "direct_manager")->first();

                if($combines) {
                    $combines->is_public = $value["is_public"];
                    $combines->save();
                }
                else {
                    CombineFieldSetting::create([
                        "field_setting_id" => $value["field_setting_id"],
                        "label_name" => "direct_manager",
                        "is_public" => $value["is_public"],
                    ]);
                }
            }

            $count++;
        }

        if($count == count($request->field_settings)) {
            return $this->successResponse("Profile field setting upgraded successfully", null, 200);
        }
        else {
            return $this->successResponse("Profile field setting failed upgrade", 400);
        }

    }
}
