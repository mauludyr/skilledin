<?php

namespace App\Repositories;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Interfaces\CustomFieldInterface;

use App\Models\CustomField;
use App\Models\Profile;
use App\Models\Grade;
use App\Models\EmploymentType;
use App\Models\SettingField;
use App\Models\CustomFieldParams;
use App\Traits\ResponseAPI;
use Illuminate\Database\Schema\Blueprint;
use Exception,Schema,DB;

class CustomFieldRepository implements CustomFieldInterface
{
    use ResponseAPI;

    // Get All Custom Field
    public function getAllCustomField()
    {
        try {
            $results = SettingField::get()->makeHidden([
                "created_at", "updated_at", "deleted_at"
            ]);
            return $this->successResponse("Get all custom field", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Find Custom Field By ID
    public function findCustomFieldById($id)
    {
        try {
            $data = SettingField::find($id);

            if (!$data) {
                return $this->errorResponse("Custom field not found", 404);
            }

            return $this->successResponse("Success to find custom field", $data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Custom Field By ID
    public function findListDropdown($id)
    {
        try {
            $data = SettingField::where("type", "Dropdown")->where('id', $id)->first();

            if (!$data) {
                return $this->errorResponse("List Dropdown not found", 404);
            }

            if ($data->alias_name == 'grade_name'){
                $results = Grade::get()->makeHidden(["grade_slug","salary_from","salary_up","benefits","created_at","updated_at","deleted_at"]);
            } else if ($data->alias_name == 'employment_type') {
                $results = EmploymentType::get()->makeHidden(["emp_type_slug","emp_type_description","created_at","updated_at","deleted_at"]);
            } else if ($data->alias_name == 'departement_name') {
                $results = null;
            } else {
                $type = DB::select(DB::raw('SHOW COLUMNS FROM profiles WHERE Field = "'.$data->alias_name.'"'))[0]->Type;
                    preg_match('/^enum\((.*)\)$/', $type, $matches);
                    $results = array();
                    foreach(explode(',', $matches[1]) as $value){
                        $results[] = trim($value, "'");
                    }
            }
            return $this->successResponse("Success to find custom field {$data->alias_name}", $results);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Save Custom Field
    public function saveCustomField(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'field_name' => 'required|string',
                'type' => 'required|string',
                'is_public' => 'required|boolean',
                'value' => 'required_if:type,Dropdown|array'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $slug = Str::slug(Str::lower(trim($request->field_name)), "_");
        $customParam = SettingField::where('alias_name', $slug)->first();

        if($customParam) {
            return $this->errorResponse("Field name {$request->field_name} has already created", 400);
        }

        if ($request->type == 'Dropdown'){
            if (count($request->value) <= 1 || count($request->value) > 10) {
                return $this->errorResponse("Minimul & Maximum Dropdown value is 2 to 10", 400);
            }
        }

        try {

            $customParam = SettingField::create([
                "field_name" => $request->field_name,
                "alias_name" => $slug,
                "type" => $request->type,
                "is_public" => $request->is_public
            ]);

            $newColumnType = $customParam->type;
            $newColumnName = $customParam->alias_name;
            if ($newColumnType == 'Dropdown'){
                $valueColumn = $request->value;
            } else {
                $valueColumn = null;
            }

            Schema::table('profiles', function (Blueprint $table) use ($newColumnType, $newColumnName, $valueColumn) {
                if($newColumnType == 'Dropdown'){
                    $table->enum($newColumnName, $valueColumn)->nullable()->default(null);
                } else {
                    $table->string($newColumnName)->nullable()->default($valueColumn);
                }

            });

            return $this->successResponse("Custom field param created successfully", $customParam, 201);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }


    // Update Custom Field
    public function updateCustomField(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'custom_fields' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        try {
            foreach ($request->custom_fields as $value) {
                $data = SettingField::find($value['id']);

                if($data) {
                    $data->is_public = $value["is_public"];
                    $data->save();
                }
                else {
                    return $this->errorResponse("Setting Field failed update", 400);
                }
            }

            return $this->successResponse("Setting Field updated successfully", SettingField::get(), 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Delete Custom Field
    public function deleteCustomField($id)
    {
        try {
            $data = SettingField::find($id);
            if (!$data) {
                return $this->errorResponse("Custom field not found", 404);
            }

            $columnName = $data->alias_name;

            Schema::table('profiles', function (Blueprint $table) use ($columnName) {
                $table->dropColumn($columnName);
            });

            $data->delete();
            return $this->successResponse("Custom field {$columnName} deleted successfully");
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
