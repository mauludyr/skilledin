<?php

namespace App\Repositories;

use App\Interfaces\JobPositionInterface;
use App\Models\JobPosition;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JobPositionRepository implements JobPositionInterface
{
    use ResponseAPI;

    // Get All Job Position
    public function getAllJobPosition()
    {
        try {
            $results = JobPosition::get();
            return $this->successResponse("Get all job position", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Job Position By ID
    public function findJobPositionById($id)
    {
        try {
            $data = JobPosition::find($id);

            if (!$data) {
                return $this->errorResponse("Job position not found", 404);
            }

            return $this->successResponse("Success to find job position", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Job Position By Slug
    public function findJobPositionBySlug($slug)
    {
        try {
            $data = JobPosition::where('job_slug', $slug)->first();

            if (!$data) {
                return $this->errorResponse("Job position not found", 404);
            }

            return $this->successResponse("Success to find job position", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Job Position
    public function saveJobPosition(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'job_name' => 'required|unique:job_positions,job_name'
            ],
            [
                'job_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();

        try {
            $data = new JobPosition();
            $data->job_name = $request->job_name;
            $data->job_slug = Str::lower(Str::slug($request->job_name));

            if($request->has('job_code')) {
                $data->job_code = $request->job_code;
            }

            $data->save();
            DB::commit();

            return $this->successResponse("Job position created successfully", $data, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function updateJobPosition(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'job_name' => 'required|unique:job_positions,job_name,' . $id
            ],
            [
                'job_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {

            $data = JobPosition::find($id);

            if (!$data) {
                return $this->errorResponse("Grade not found", 404);
            }

            $data->job_name = $request->job_name;
            $data->job_slug = Str::lower(Str::slug($request->job_name));

            if($request->has('job_code')) {
                $data->job_code = $request->job_code;
            }

            $data->save();

            DB::commit();

            return $this->successResponse("Job position updated successfully", $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    // Delete Job Position
    public function deleteJobPosition($id)
    {
        DB::beginTransaction();
        try {
            $data = JobPosition::find($id);

            if (!$data) {
                return $this->errorResponse("Job position not found", 404);
            }

            $name = $data->job_name;
            $data->delete();

            DB::commit();

            return $this->successResponse("Job position {$name} deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
