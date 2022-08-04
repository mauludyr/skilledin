<?php

namespace App\Repositories;

use App\Interfaces\MeasureInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Measure;

use App\Traits\ResponseAPI;
use Exception;


class MeasureRepository implements MeasureInterface
{
    use ResponseAPI;

    // Get All Measures
    public function getAllMeasures()
    {
        try {
            $results = Measure::get();
            return $this->successResponse("Get all measures", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Measure By Id
    public function findMeasureById($id)
    {
        try {
            $data = Measure::find($id);

            if (!$data) {
                return $this->errorResponse("Measure not found", 404);
            }

            return $this->successResponse("Success to find measure", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Measure By Slug
    public function findMeasureBySlug($slug)
    {
        try {
            $data = Measure::where('measure_slug', $slug)->first();

            if (!$data) {
                return $this->errorResponse("Measure not found", 404);
            }

            return $this->successResponse("Success to find measure", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Savae Measure
    public function saveMeasure(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'measure_name' => 'required|unique:measures,measure_name'
            ],
            [
                'measure_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        $name = trim($request->measure_name);
        $slug = Str::slug(Str::lower($name));

        try {
            $data = Measure::create([
                'measure_name' => $name,
                'measure_slug' => $slug
            ]);

            return $this->successResponse("Measure created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Measure
    public function updateMeasure(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'measure_name' => 'required|unique:measures,measure_name,' . $id
            ],
            [
                'measure_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        $data = Measure::find($id);

        if(!$data) {
            return $this->errorResponse("Measure not found", 404);
        }

        $name = trim($request->measure_name);
        $slug = Str::slug(Str::lower($name));

        try {

            $data->measure_name = $name;
            $data->measure_slug = $slug;
            $data->save();
            return $this->successResponse("Measure created successfully", $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }
}
