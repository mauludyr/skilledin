<?php

namespace App\Repositories;

use App\Interfaces\DurationPerformanceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Traits\ResponseAPI;
use App\Models\DurationTime;
use App\Models\FrequencyPeriod;
use App\Models\PeriodTime;
use App\Models\ReviewsDate;
use Exception;
use Illuminate\Support\Carbon;
use SebastianBergmann\Timer\Duration;

class DurationPerformanceRepository implements DurationPerformanceInterface
{
    use ResponseAPI;

    private function queryDurationTime()
    {
        return DurationTime::with([
            "reviewDate",
            "periodTime"
        ]);
    }

    // Get All Duration & Performance Time Period Data
    public function getAllDurationPerformance()
    {

        try {
            $data = $this->queryDurationTime()->orderBy("id", "asc")->get()->makeHidden([
                "created_at", "updated_at"
            ]);

            return $this->successResponse("Get all duration & performance time period", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Duration & Performance Time Period Data By ID
    public function findDurationPerformanceById($id)
    {
        try {
            $data = $this->queryDurationTime()->find($id);

            return $this->successResponse("Find duration & performance time period", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Duration & Performance Time Period Data
    public function saveDurationPerformance(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'period_time_id' => 'required',
                'duration_start' => 'required',
                'duration_end' => 'required',
                'reporting_year' => 'required'
            ],
            [
                'period_time_id.required' => 'The :attribute field can not be blank value.',
                'duration_start.required' => 'The :attribute field can not be blank value.',
                'duration_end.required' => 'The :attribute field can not be blank value.',
                'reporting_year.required' => 'The :attribute field can not be blank value.',

            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $periodTime = PeriodTime::find($request->period_time_id);

        if(!$periodTime) {
            return $this->errorResponse("Time period not found", 404);
        }


        $reviewDate = ReviewsDate::where("year", Carbon::now()->format('Y'))->first();

        if(!$reviewDate) {
            $reviewDate = ReviewsDate::create([
                "month" => "January",
                "year" => Carbon::now()->format('Y'),
                "frequency_id" => FrequencyPeriod::where("slug", "quarterly")->first()->id
            ]);
        }


        $durationTime = DurationTime::where('period_time_id', $periodTime->id)
            ->where('reporting_year', $request->reporting_year)->first();

        if($durationTime) {
            return $this->errorResponse("I'm sorry {$periodTime->period_name} {$durationTime->reporting_year} has already exists", 400);
        }

        try {
            $data = DurationTime::create([
                "period_time_id" => $periodTime->id,
                "review_date_id" => $reviewDate->id,
                "duration_start" => $request->duration_start,
                "duration_end" => $request->duration_end,
                "performance_start" => $request->performance_start ?? null,
                "performance_end" => $request->performance_end ?? null,
                "reporting_year" => $request->reporting_year,
            ]);

            return $this->successResponse("Create new time period of duration and performace successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Duration & Performance Time Period Data
    public function updateDurationPerformance(Request $request, $id)
    {
        $data = DurationTime::find($id);

        if(!$data) {
            return $this->errorResponse("Duration & performance of time period not found", 404);
        }

        if($request->period_time_id && $request->period_time_id != $data->period_time_id) {
            $data->period_time_id = $request->period_time_id;
        }

        try {

            $data->duration_start = $request->duration_start ?? $data->duration_start;
            $data->duration_end = $request->duration_end ?? $data->duration_end;
            $data->performance_start = $request->performance_start ?? $data->performance_start;
            $data->performance_end = $request->performance_end ?? $data->performance_end;
            $data->reporting_year = $request->reporting_year ?? $data->reporting_year;
            $data->is_include = $request->is_include ?? $data->is_include;
            $data->save();

            return $this->successResponse("Duration & performance of time period updated successfully", $data);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Delete Duration & Performance Time Period Data
    public function deleteDurationPerformance($id)
    {
        $data = DurationTime::find($id);

        if(!$data) {
            return $this->errorResponse("Duration & performance of time period not found", 404);
        }
        
        try {
            $name = $data->periodTime->period_name . " ". $data->reporting_year;
            $data->delete();

            return $this->successResponse("{$name} success deleted", null);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
