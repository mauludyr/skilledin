<?php

namespace App\Repositories;

use App\Helpers\ProfileAccount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Interfaces\ProfileInterface;
use App\Models\Profile;
use App\Models\ParticularChange;
use App\Models\LogParticularChange;
use App\Models\User;
use App\Traits\ResponseAPI;
use App\Traits\UserManagement;
use Exception;
use Illuminate\Support\Carbon;

class ProfileRepository implements ProfileInterface
{
    use ResponseAPI, UserManagement;

    private function getFileName($id, $extension)
    {
        return "SKILLEDIN_USER_{$id}_FILE_". time() . "." . $extension;
    }

    private function removeDirectory($path)
    {
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0777, true, true);
        }
        else {
            Storage::deleteDirectory($path);
        }
    }

    private function uploadFromFile(Request $req, $userId, $fieldName = 'image_profile')
    {
        $file = $req->file($fieldName);
        $path = "public/avatars/user_{$userId}";

        $extension = $file->extension();
        $filename = $this->getFileName($userId, $extension);

        $this->removeDirectory($path);

        Storage::put("{$path}/{$filename}", $file);
        $filepath =  env("APP_URL"). "/storage/avatars/user_{$userId}/{$filename}";

        return (object) [
            'filename' => $filename,
            'filepath' => $filepath,
        ];
    }

    private function attachmentFromFile(Request $req, $userId, $fieldName = 'attachment')
    {
        $file = $req->file($fieldName);
        $path = "public/particular_file/user_{$req->field_name}_{$userId}";

        $extension = $file->extension();
        $filename = $this->getFileName($userId, $extension);

        $this->removeDirectory($path);

        Storage::put("{$path}/{$filename}", $file);
        $filepath =  env("APP_URL"). "/storage/particular_file/user_{$req->field_name}_{$userId}/{$filename}";

        return (object) [
            'filename' => $filename,
            'filepath' => $filepath,
        ];
    }

    private function uploadFromBase64(Request $req, $userId, $fieldName = 'image_profile')
    {
        $image_parts = explode(";base64,", $req->input($fieldName));
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $path = "public/avatars/user_{$userId}";
        $filename = $this->getFileName($userId, $image_type);
        $this->removeDirectory($path);

        Storage::put("{$path}/{$filename}", $image_base64);
        $filepath =  env("APP_URL"). "/storage/avatars/user_{$userId}/{$filename}";

        return (object) [
            'filename' => $filename,
            'filepath' => $filepath,
        ];
    }

    public function saveProfile(Request $req)
    {
        $user = $req->user();

        $validator = Validator::make(
            $req->all(),
            [
                'first_name' => 'required',
                'birthday' => 'required',
                'address' => 'required',
                'nationality_id' => 'required',
            ],
            [
                'first_name.required' => 'The :attribute field can not be blank value.',
                'birthday.required' => 'The :attribute field can not be blank value.',
                'nationality_id.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $imageProperty = null;

        if($req->hasFile('image_profile')) {
            $imageProperty = $this->uploadFromFile($req, $req->user_id, 'image_profile');
        }

        try {
            $data = Profile::create([
                'user_id' => $user->id,
                'first_name' => $req->first_name,
                'last_name' => $req->last_name ?? null,
                'middle_name' => $req->middle_name ?? null,
                'birthday' =>  $req->birthday ?? null,
                'pronouns'  => $req->pronouns ?? null,
                'superpower' => $req->superpower ?? null,
                'address' => $req->address ?? null,
                'phone_number' => $req->phone_number ?? null,
                'emergency_contact_name'  => $req->emergency_contact_name ?? null,
                'emergency_contact_number' => $req->emergency_contact_number ?? null,
                'date_joined' => $req->date_joined ?? null,
                'image_filename' => $imageProperty == null ? null : $imageProperty->filename,
                'image_filepath' => $imageProperty == null ? null : $imageProperty->filepath,
                'nationality_id' => $req->nationality_id ?? null,
                'location_name' => $req->location_name ?? null,
                'location_id' => $req->location_id ?? null,
                'identity' => $req->identity ?? null,
            ]);

            if(!$data) {
                return $this->errorResponse("Failed to save profile data", 400);
            }

            $fullname = $this->combineToFullname($data->first_name, $data->middle_name, $data->last_name);

            $user = User::find($user->id);
            $user->name = $fullname;
            $user->save();

            return $this->successResponse("Profile updated successfully", $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function saveParticularChange(Request $req)
    {

        $validator = Validator::make(
            $req->all(),
            [
                'particular.*.field_name' => 'required',
                'particular.*.current_value' => 'required',            
                'particular.*.attachment' => 'nullable|file'            
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {

            $profile = new Profile;
            $columns = $profile->getTableColumns();
            foreach ($req->particular as $key => $value) {
                if(!in_array($value['field_name'], $columns)){
                    return $this->errorResponse("Field Name not Found in Profile", 400);
                }

                if($value['attachment']!=null) {
                    $file = $value['attachment'];
                    $extension = $file->extension();
                    $path = "public/particular_file/user_{$value['field_name']}_".Auth()->user()->id;
                    $filename = $this->getFileName(Auth()->user()->id, $extension);
                    $this->removeDirectory($path);

                    Storage::put("{$path}/{$filename}", $file);
                    $filepath =  env("APP_URL"). "/storage/particular_file/user_{$value['field_name']}_".Auth()->user()->id."/{$filename}";
                } else {
                    $filename = null;
                    $filepath = null;
                }
                
                $data = ParticularChange::create([
                    'user_id' => Auth()->user()->id,
                    'field_name' => $value['field_name'],
                    'field_type' => 'Particular Change',
                    'current_value' => $value['current_value'],
                    'attachment_name' => $filename,
                    'attachment_path' => $filepath
                ]);
            }

            if(!$data) {
                return $this->errorResponse("Failed to particular change", 400);
            }

            return $this->successResponse("Particular successfull to change", 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function particularChangeStatus(Request $req, $id)
    {

        $validator = Validator::make(
            $req->all(),
            [
                'status' => 'required|in:approve,reject',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $particular = ParticularChange::find($id);

            if(!$particular){
                return $this->errorResponse("Particular not Found", 400);
            } else {
                $data = LogParticularChange::create([
                    'user_id' => Auth()->user()->id,
                    'field_name' => $particular->field_name,
                    'field_type' => $particular->field_type,
                    'old_value' => $particular->old_value,
                    'current_value' => $particular->current_value,
                    'attachment_name' => $particular->attachment_name,
                    'attachment_path' => $particular->attachment_path,
                    'status' => $req->status
                ]);
                $particular->delete();
            }

            return $this->successResponse("Particular status is {$req->status} ", 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function updateProfile(Request $req, $id)
    {
        $user = $req->user();
        $imageProperty = null;

        if($req->hasFile('image_profile')) {
            $imageProperty = $this->uploadFromFile($req, $user->id, 'image_profile');
        }
        else {
            if($req->has('image_profile')) {
                $imageProperty = $this->uploadFromBase64($req, $user->id, 'image_profile');
            }
        }

        $data = Profile::with(['user'])->find($id);

        if(!$data) {
            return $this->errorResponse("Profile not found", 404);
        }

        // if($data->user_id != $user->id) {
        //     return $this->errorResponse("You cannot update this profile. Profile parameters do not match with Authorization ID", 401);
        // }

        try {

            $data->first_name = $req->first_name ?? $data->first_name;
            $data->last_name = $req->last_name ?? $data->last_name;
            $data->middle_name = $req->middle_name ?? $data->middle_name;
            $data->birthday = $req->birthday ?? $data->birthday;
            $data->pronouns = $req->pronouns ?? $data->pronouns;
            $data->superpower = $req->superpower ?? $data->superpower;
            $data->address = $req->address ?? $data->address;
            $data->phone_number = $req->phone_number ?? $data->phone_number;

            $data->emergency_contact_name = $req->emergency_contact_name ??$data->emergency_contact_name;

            $data->emergency_contact_number = $req->emergency_contact_number ??  $data->emergency_contact_number;

            $data->date_joined = $req->date_joined ?? $data->date_joined;

            if($imageProperty != null) {
                $data->image_filename = $imageProperty->filename;
                $data->image_filepath = $imageProperty->filepath;
            }


            $data->nationality_id = $req->has('nationality_id') ? $req->nationality_id : $data->nationality_id;
            $data->location_id = $req->has('location_id') ? $req->location_id : $data->location_id;

            $data->location_name = $req->location_name ?? $data->location_name;
            $data->identity = $req->identity ?? $data->identity;
            $data->save();

            if(!$data) {
                return $this->errorResponse("Failed to update profile data", 400);
            }

            $fullname = $this->combineToFullname($data->first_name, $data->middle_name, $data->last_name);

            $data->user()->update(['name' => $fullname]);


            return $this->successResponse("Profile update successfully", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update User Avatar
    public function updateUserAvatar(Request $request)
    {
        $user = $request->user();
        $imageProperty = null;

        if($request->hasFile('image_profile'))
        {
            $imageProperty = $this->uploadFromFile($request, $user->id, 'image_profile');
        }
        else {
            $imageProperty = $this->uploadFromBase64($request, $user->id, 'image_profile');
        }

        try {
            $data = Profile::where("user_id", $user->id)->first();
            if($imageProperty != null) {
                $data->image_filename = $imageProperty->filename;
                $data->image_filepath = $imageProperty->filepath;
            }
            $data->save();
            return $this->successResponse("User avatar upload successfully", $data);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }

    // Get All Profile Schema
    public function getAllProfileSchema()
    {
        $data = new ProfileAccount();
        return $this->successResponse("List of table field", $data->getAccountColumnListing());
    }

    // Get Particular
    public function getParticular()
    {
        $data = ParticularChange::where('user_id', Auth()->user()->id)->get();
        return $this->successResponse("Particular List", $data);
    }

    // Get Particular
    public function getParticularLog()
    {
        $data = LogParticularChange::with('user')->where('user_id', Auth()->user()->id)->get();
        return $this->successResponse("Particular Log History", $data);
    }


    //Update Profile By Auth
    public function updateProfileByAuth(Request $req)
    {
        $user = $req->user();
        $imageProperty = null;

        if($req->hasFile('image_profile')) {
            $imageProperty = $this->uploadFromFile($req, $user->id, 'image_profile');
        }
        else {
            if($req->has('image_profile')) {
                $imageProperty = $this->uploadFromBase64($req, $user->id, 'image_profile');
            }
        }

        $data = Profile::with(['user'])->where("user_id", $user->id)->first();

        if(!$data) {
            try {
                $data = Profile::create([
                    'user_id' => $user->id,
                    'first_name' => $req->first_name,
                    'last_name' => $req->last_name ?? null,
                    'middle_name' => $req->middle_name ?? null,
                    'birthday' =>  $req->birthday ?? null,
                    'pronouns'  => $req->pronouns ?? null,
                    'superpower' => $req->superpower ?? null,
                    'address' => $req->address ?? null,
                    'phone_number' => $req->phone_number ?? null,
                    'emergency_contact_name'  => $req->emergency_contact_name ?? null,
                    'emergency_contact_number' => $req->emergency_contact_number ?? null,
                    'date_joined' => $req->date_joined ?? null,
                    'image_filename' => $imageProperty == null ? null : $imageProperty->filename,
                    'image_filepath' => $imageProperty == null ? null : $imageProperty->filepath,
                    'nationality_id' => $req->nationality_id ?? null,
                    'location_id' => $req->location_id ?? null,
                    'location_name' => $req->location_name ?? null,
                    'identity' => $req->identity ?? null,
                ]);

                if(!$data) {
                    return $this->errorResponse("Failed to save profile data", 400);
                }

                $fullname = $this->combineToFullname($data->first_name, $data->middle_name, $data->last_name);

                $user = User::find($user->id);
                $user->name = $fullname;
                $user->save();

                return $this->successResponse("Profile updated successfully", $data, 201);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }
        }
        else {
            // Update
            try {
                $data->first_name = $req->first_name ?? $data->first_name;
                $data->last_name = $req->last_name ?? $data->last_name;
                $data->middle_name = $req->middle_name ?? $data->middle_name;
                $data->birthday = $req->birthday ?? $data->birthday;
                $data->pronouns = $req->pronouns ?? $data->pronouns;
                $data->superpower = $req->superpower ?? $data->superpower;
                $data->address = $req->address ?? $data->address;
                $data->phone_number = $req->phone_number ?? $data->phone_number;

                $data->emergency_contact_name = $req->emergency_contact_name ??$data->emergency_contact_name;

                $data->emergency_contact_number = $req->emergency_contact_number ??  $data->emergency_contact_number;

                $data->date_joined = $req->date_joined ?? $data->date_joined;

                if($imageProperty != null) {
                    $data->image_filename = $imageProperty->filename;
                    $data->image_filepath = $imageProperty->filepath;
                }

                $data->nationality_id = $req->has('nationality_id') ? $req->nationality_id : $data->nationality_id;
                $data->location_id = $req->has('location_id') ? $req->location_id : $data->location_id;

                $data->location_name = $req->location_name ?? $data->location_name;
                $data->identity = $req->identity ?? $data->identity;
                $data->save();

                if(!$data) {
                    return $this->errorResponse("Failed to update profile data", 400);
                }

                $fullname = $this->combineToFullname($data->first_name, $data->middle_name, $data->last_name);

                $data->user()->update(['name' => $fullname]);

                return $this->successResponse("Profile update successfully", $data);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }
        }
    }

    // Delete Particular Log
    public function deleteParticularLog($id)
    {
        try {
            $data = LogParticularChange::find($id);
            if (!$data) {
                return $this->errorResponse("Log Particular not found", 404);
            }

            $data->delete();
            return $this->successResponse("Log Particular deleted successfully");
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
