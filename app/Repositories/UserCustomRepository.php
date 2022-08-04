<?php

namespace App\Repositories;

use App\Helpers\ProfileAccount;
use App\Interfaces\UserCustomInterface;
use App\Models\CustomField;
use App\Models\UserCustomField;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\Storage;

class UserCustomRepository implements UserCustomInterface
{
    use ResponseAPI;

    private function getFileName($id, $extension)
    {
        return "CUSTOM_FIELD_{$id}_FILE_". time() . "." . $extension;
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

    private function storeFile(Request $request, $id)
    {
        if($request->hasFile("value")) {
            $file = $request->file("value");
            $path = "public/custom_fields/id_{$id}";

            $extension = $file->extension();
            $filename = $this->getFileName($id, $extension);

            $this->removeDirectory($path);

            Storage::put("{$path}/{$filename}", $file);
            return env("APP_URL"). "/storage/custom_fields/id_{$id}/{$filename}";
        }

        return null;
    }

    private function storeBase64Encode(Request $request, $id)
    {
        if($request->input("value") && !empty($request->input("value"))) {
            $image_parts = explode(";base64,", $request->input("value"));
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $path = "public/custom_fields/id_{$id}";

            $filename = $this->getFileName($id, $image_type);
            $this->removeDirectory($path);

            Storage::put("{$path}/{$filename}", $image_base64);
            return env("APP_URL"). "/storage/custom_fields/id_{$id}/{$filename}";
        }
        return null;
    }

    private function generateValue($customField, $request)
    {

        if($customField->custom_type->name == "array") {
            $newValue = json_encode($request->value);
        }
        else if($customField->custom_type->name == "file") {
            $newValue = $this->storeFile($request, $customField->id);
        }
        else if($customField->custom_type->name == "base64encode")
        {
            $newValue = $this->storeBase64Encode($request, $customField->id);
        }
        else {
            $newValue = $request->value;
        }

        return $newValue;
    }

    public function saveUserCustomField(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make(
            $request->all(),
            [
                'custom_field_id' => 'required|unique:user_custom_fields,custom_field_id',
                'value' => 'required',
                'field_setting_id' => 'required',

            ],
            [
                'custom_field_id.required' => 'The :attribute field can not be blank value.',
                'value.required' => 'The :attribute field can not be blank value.',
                'field_setting_id.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $customField = CustomField::with([
            'customParam' => function($q) {
                $q->select("id", "name", "slug");
            },
            'customType' => function($q) {
                $q->select("id", "name");
            },
        ])->find($request->custom_field_id);


        if(!$customField) {
            return $this->errorResponse("Custom field id {$request->custom_field_id} not found", 404);
        }

        $value = $this->generateValue($customField, $request);

        try {
            $data = UserCustomField::create([
                "user_id" => $user->id,
                "custom_field_id" => $request->custom_field_id,
                "value" => $value,
                "field_setting_id" => $request->field_setting_id ?? null
            ]);

            return $this->successResponse("Set custom field to user", $data);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    public function updateUserCustomField(Request $request)
    {

    }

    public function deleteUserCustomField($id)
    {

    }

}
