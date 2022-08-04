<?php

namespace App\Repositories;

use App\Interfaces\OkrPotentialInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OkrPotential;
use App\Traits\ResponseAPI;
use Exception;

class OkrPotentialRepository implements OkrPotentialInterface
{
    use ResponseAPI;

    private function queryLevel()
    {
        return OkrPotential::orderBy('id', 'asc');
    }


    //Get All OKR Potential
    public function getAllOkrPotential()
    {
        try {
            $results = $this->queryLevel()->where('is_active', 1)->get()->makeHidden(['created_at','updated_at','deleted_at']);
            return $this->successResponse("Get all OKR Potentials", $results);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Find OKR Potential By ID
    public function findOkrPotentialById($id)
    {
        try {
            $data = OkrPotential::find($id);

            if (!$data) {
                return $this->errorResponse("OKR Potential not found", 404);
            }

            return $this->successResponse("Success to find OKR Potential", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Find OKR Potential By Slug
    public function findOkrPotentialBySlug($slug)
    {
        try {
            $data = OkrPotential::where('potential_slug', $slug)->first();

            if (!$data) {
                return $this->errorResponse("OKR Potential not found", 404);
            }

            return $this->successResponse("Success to find OKR Potential", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Save OKR Potential
    public function saveOkrPotential(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'potential_name' => 'required|unique:okr_potentials,potential_name'
            ],
            [
                'potential_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        $name = ucwords(trim($request->potential_name));
        $slug = Str::slug(Str::lower($name));

        try {
            $data = OkrPotential::create([
                'potential_name' => $name,
                'potential_slug' => $slug
            ]);

            return $this->successResponse("OKR Potential created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Update OKR Potential
    public function updateOkrPotential(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'potential_name' => 'required|unique:okr_potentials,potential_name,' . $id
            ],
            [
                'potential_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        $data = OkrPotential::find($id);

        if(!$data) {
            return $this->errorResponse("OKR Potential not found", 404);
        }

        $name = ucwords(trim($request->potential_name));
        $slug = Str::slug(Str::lower($name));

        try {

            $data->potential_name = $name;
            $data->potential_slug = $slug;
            $data->save();
            return $this->successResponse("Potential created successfully", $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }
}
