<?php

namespace App\Repositories;

use App\Interfaces\NationalityInterface;
use App\Models\Nationality;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NationalityRepository implements NationalityInterface
{
    use ResponseAPI;

    // Get All Nationality
    public function getAllNationality()
    {
        try {
            $results = Nationality::get();
            return $this->successResponse(
                "Get all nationalities",
                $results
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    // Find Nationality By Id
    public function findNationalityById($id)
    {
        try {
            $data = Nationality::find($id);

            if (!$data) {
                return $this->errorResponse("Nationality not found", 404);
            }

            return $this->successResponse(
                "Success to find nationality",
                $data
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    // Find Nationality By Code
    public function findNationalityByCode($code)
    {
        try {
            $data = Nationality::where('nationality_code', $code)->first();

            if (!$data) {
                return $this->errorResponse("Nationality not found", 404);
            }

            return $this->successResponse("Success to find nationality", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Nationality
    public function saveNationality(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nationality_name' => 'required|unique:nationalities,nationality_name',
            ],
            [
                'nationality_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();

        try {
            $data = new Nationality();
            $data->nationality_name = $request->nationality_name;

            if($request->has('nationality_code')) {
                $data->nationality_code = Str::lower($request->nationality_code);
            }

            $data->save();
            DB::commit();

            return $this->successResponse("Nationality created successfully", $data, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Nationality
    public function updateNationality(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nationality_name' => 'required|unique:nationalities,nationality_name,' . $id
            ],
            [
                'nationality_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {

            $data = Nationality::find($id);

            if (!$data) {
                return $this->errorResponse("Nationality not found", 404);
            }

            $data->nationality_name = $request->nationality_name;

            if($request->has('nationality_code')) {
                $data->nationality_code = Str::lower($request->nationality_code);
            }

            $data->save();

            DB::commit();

            return $this->successResponse("Nationality updated successfully", $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    // Delete Nationality
    public function deleteNationality($id)
    {
        DB::beginTransaction();
        try {
            $data = Nationality::find($id);

            if (!$data) {
                return $this->errorResponse("Nationality not found", 404);
            }

            $name = $data->nationality_name;
            $data->delete();

            DB::commit();

            return $this->successResponse("Nationality {$name} deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
