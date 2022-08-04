<?php

namespace App\Repositories;

use App\Interfaces\ObjectiveLevelInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ObjectiveLevel;
use App\Traits\ResponseAPI;
use Exception;

class ObjectiveLevelRepository implements ObjectiveLevelInterface
{
    use ResponseAPI;

    private function queryLevel()
    {
        return ObjectiveLevel::orderBy('id', 'asc');
    }


    //Get All Objective Level
    public function getAllObjectiveLevel()
    {
        try {
            $results = $this->queryLevel()->get();
            return $this->successResponse("Get all objective levels", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Find Objective Level By ID
    public function findObjectiveLevelById($id)
    {
        try {
            $data = ObjectiveLevel::find($id);

            if (!$data) {
                return $this->errorResponse("Objective level not found", 404);
            }

            return $this->successResponse("Success to find objective level", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Find Objective Level By Slug
    public function findObjectiveLevelBySlug($slug)
    {
        try {
            $data = ObjectiveLevel::where('level_slug', $slug)->first();

            if (!$data) {
                return $this->errorResponse("Objective level not found", 404);
            }

            return $this->successResponse("Success to find objective level", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Save Objective Level
    public function saveObjectiveLevel(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'level_name' => 'required|unique:objective_levels,level_name'
            ],
            [
                'level_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        $name = ucwords(trim($request->level_name));
        $slug = Str::slug(Str::lower($name));

        try {
            $data = ObjectiveLevel::create([
                'level_name' => $name,
                'level_slug' => $slug
            ]);

            return $this->successResponse("Objective level created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Update Objective Level
    public function updateObjectiveLevel(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'level_name' => 'required|unique:objective_levels,level_name,' . $id
            ],
            [
                'level_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        $data = ObjectiveLevel::find($id);

        if(!$data) {
            return $this->errorResponse("Objective level not found", 404);
        }

        $name = ucwords(trim($request->level_name));
        $slug = Str::slug(Str::lower($name));

        try {

            $data->level_name = $name;
            $data->level_slug = $slug;
            $data->save();
            return $this->successResponse("Objective created successfully", $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }
}
