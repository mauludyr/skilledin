<?php

namespace App\Repositories;

use App\Interfaces\LocationInterface;
use App\Models\Location;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LocationRepository implements LocationInterface
{
    use ResponseAPI;

    // Get All Location
    public function getAllLocation()
    {

        try {
            $results = Location::get();

            return $this->successResponse(
                "Get all locations",
                $results
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    // Find Location By ID
    public function findLocationById($id)
    {
        try {
            $data = Location::find($id);

            if (!$data) {
                return $this->errorResponse("Location not found", 404);
            }

            return $this->successResponse(
                "Success to find location",
                $data
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    // Find Location By Code
    public function findLocationByCode($code)
    {
        try {
            $data = Location::where('location_code', $code)->first();

            if (!$data) {
                return $this->errorResponse("Location not found", 404);
            }

            return $this->successResponse("Success to find location", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Location
    public function saveLocation(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'location_name' => 'required|unique:locations,location_name',
            ],
            [
                'location_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();

        try {
            $data = new Location();
            $data->location_name = $request->location_name;

            if($request->has('location_code')) {
                $data->location_code = Str::lower($request->location_code);
            }

            $data->save();
            DB::commit();

            return $this->successResponse("Location created successfully", $data, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Location
    public function updateLocation(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'location_name' => 'required|unique:locations,location_name,' . $id
            ],
            [
                'location_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {

            $data = Location::find($id);

            if (!$data) {
                return $this->errorResponse("Location not found", 404);
            }

            $data->location_name = $request->location_name;

            if($request->has('location_code')) {
                $data->location_code = Str::lower($request->location_code);
            }

            $data->save();

            DB::commit();

            return $this->successResponse("Location updated successfully", $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }


    // Delete Location
    public function deleteLocation($id)
    {
        DB::beginTransaction();
        try {
            $data = Location::find($id);

            if (!$data) {
                return $this->errorResponse("Location not found", 404);
            }

            $name = $data->location_name;
            $data->delete();

            DB::commit();

            return $this->successResponse("Location {$name} deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

}
