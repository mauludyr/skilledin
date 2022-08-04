<?php

namespace App\Repositories;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Interfaces\EmploymentInterface;
use App\Models\Employment;

use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;

class EmploymentRepository implements EmploymentInterface
{
    use ResponseAPI;

    public function saveEmployment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'grade_id' => 'required',
                'job_position_id' => 'required',
                'employment_type_id' => 'required',
                'salary_id' => 'required',
                'salary' => 'required'
            ],
            [
                'user_id.required' => 'The :attribute field can not be blank value.',
                'grade_id.required' => 'The :attribute field can not be blank value.',
                'job_position_id.required' => 'The :attribute field can not be blank value.',
                'employment_type_id.required' => 'The :attribute field can not be blank value.',
                'salary_id.required' => 'The :attribute field can not be blank value.',
                'salary.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $data = Employment::create([
                'user_id' => $request->user_id,
                'grade_id' => $request->grade_id,
                'job_position_id' => $request->job_position_id,
                'employment_type_id' => $request->employment_type_id,
                'salary_id' => $request->salary_id,
                'salary' => $request->salary
            ]);

            return $this->successResponse("Employment created successfully", $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function updateEmployment(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
            ],
            [
                'user_id.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {
            $data = Employment::find($id);

            $data->grade_id = $request->has('grade_id') ? $request->grade_id : $data->grade_id;
            $data->job_position_id = $request->has('job_position_id') ? $request->job_position_id : $data->job_position_id;
            $data->employment_type_id = $request->has('employment_type_id') ? $request->employment_type_id : $data->employment_type_id;
            $data->salaray_id = $request->salary_id ?? $data->salary_id;
            $data->salary = $request->has('salary') ? $request->salary : $data->salary;
            $data->save();

            DB::commit();

            return $this->successResponse("Employment updated successfully", $data, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
