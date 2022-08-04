<?php

namespace App\Repositories;

use App\Interfaces\GradeInterface;
use App\Models\Grade;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GradeRepository implements GradeInterface
{
    use ResponseAPI;


    private function toLowerCase($text) {
        return Str::lower(trim($text));
    }

    // Get All Grade
    public function getAllGrade()
    {
        try {
            $results = Grade::get()->makeHidden("created_at", "updated_at");
            return $this->successResponse("Get all grade", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Grade By Id
    public function findGradeById($id)
    {
        try {
            $data = Grade::find($id);
            if (!$data) {
                return $this->errorResponse("Grade not found", 404);
            }

            return $this->successResponse("Success to find grade", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Grade By Slug
    public function findGradeBySlug($slug)
    {
        $slug = $this->toLowerCase($slug);

        try {
            $data = Grade::where('grade_slug', $slug)->first();

            if (!$data) {
                return $this->errorResponse("Grade not found", 404);
            }

            return $this->successResponse("Success to find grade", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Grade
    public function saveGrade(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'grade.*.grade_name' => 'required|unique:grades,grade_name'
            ],
            [
                'grade.*.grade_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            foreach ($request->grade as $key => $value) {
                $data = Grade::create([
                    "grade_name" => ucwords($this->toLowerCase($value['grade_name'])),
                    "grade_slug" => $this->toLowerCase($value['grade_name']),
                    "salary_from" => $value['salary_from'] ?? null,
                    "salary_up" => $value['salary_up'] ?? null,
                    "benefits" => $value['benefits'] ?? null
                ]);
            }

            return $this->successResponse("Grade created successfully", $data, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Grade
    public function updateGrade(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'grade_name' => 'required|unique:grades,grade_name,'.$id
            ],
            [
                'grade_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = Grade::find($id);

        if (!$data) {
            return $this->errorResponse("Grade not found", 404);
        }

        try {
            $data->grade_name = $request->grade_name ? ucwords($this->toLowerCase($request->grade_name)) : $data->grade_name;
            $data->grade_slug = $request->grade_name ? $this->toLowerCase($request->grade_name) : $data->grade_slug;
            $data->salary_from = $request->salary_from ?? null;
            $data->salary_up = $request->salary_up ?? null;
            $data->benefits = $request->benefits ?? null;
            $data->save();

            return $this->successResponse("Grade updated successfully", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    // Delete Grade
    public function deleteGrade($id)
    {
        try {
            $data = Grade::find($id);

            if (!$data) {
                return $this->errorResponse("Grade not found", 404);

            }
            $name = $data->grade_name;
            $data->delete();
            return $this->successResponse("Grade {$name} deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
