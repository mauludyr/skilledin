<?php

namespace App\Repositories;

use App\Interfaces\PeriodTimeInterface;
use App\Models\PeriodTime;
use App\Models\ReviewsDate;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PeriodTimeRepository implements PeriodTimeInterface
{
    use ResponseAPI;

    private function queryPeriod()
    {
        return PeriodTime::with([
            "durations"
        ]);
    }

    public function getAllPeriodByReviewDate()
    {
        $year = Carbon::now()->format('Y');
        $reviewDate = ReviewsDate::with(['frequencyPeriod'])->where('year', $year)->first();

        $periods = [];

        switch ($reviewDate->frequencyPeriod->name) {
            case 'Quarterly':
                $periods = PeriodTime::whereNotIn("period_slug", ["h1", "h2"])->get();
                break;

            case 'Half Year':
                $periods = PeriodTime::whereIn("period_slug", ["h1", "h2", "fy"])->get();
                break;

            default:
                $periods = PeriodTime::whereIn("period_slug", ["fy"])->get();
                break;
        }


        return $this->successResponse("Get all period by review date", ["periods" => $periods, "reviewDate" => $reviewDate]);

    }

    // Get All Data Period
    public function getAllPeriod()
    {
        try {
            $results = $this->queryPeriod()->orderBy('id', 'asc')
                ->get()->makeHidden([
                    "created_at", "updated_at", "deleted_at"
                ]);
            return $this->successResponse("Get all period", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Period Data
    public function savePeriod(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'period_name' => 'required|unique:period_times,period_name',
            ],
            [
                'period_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $data = $this->queryPeriod()->create([
                "period_name" => Str::upper($request->period_name),
                "period_slug" => Str::slug(Str::lower($request->period_name))
            ]);

            return $this->successResponse("Period time created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Period Data
    public function updatePeriod(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'period_name' => 'required|unique:period_times,period_name,' . $id
            ],
            [
                'period_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = PeriodTime::find($id);

        if (!$data) {
            return $this->errorResponse("Period time not found", 404);
        }


        try {

            $data->period_name = $request->period_name ? Str::upper($request->period_name) : $data->period_name;
            $data->period_slug = $request->period_name ? Str::slug(Str::lower($request->period_name)) : $data->period_slug;
            $data->save();
            return $this->successResponse("Period time updated successfully", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Delete Period Data
    public function deletePeriod($id)
    {
        try {
            $data = $this->queryPeriod()->find($id);

            if (!$data) {
                return $this->errorResponse("Period time not found", 404);
            }

            $name = $data->period_name;
            $data->delete();

            return $this->successResponse("Period time {$name} deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
