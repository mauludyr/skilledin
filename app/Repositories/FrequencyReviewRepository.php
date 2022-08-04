<?php

namespace App\Repositories;

use App\Models\DurationTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Exception;
use App\Models\FrequencyPeriod;
use App\Models\ReviewsDate;
use App\Traits\ResponseAPI;
use Illuminate\Support\Carbon;
use SebastianBergmann\Timer\Duration;
use App\Interfaces\FrequencyReviewInterface;

class FrequencyReviewRepository implements FrequencyReviewInterface
{
    use ResponseAPI;

    private function queryReviewDate()
    {
        return ReviewsDate::with([
            "frequencyPeriod"
        ]);
    }

    public function getReviewDate()
    {
        try {
            $data = $this->queryReviewDate()->where("year", Carbon::now()->format("Y"))->first();
            return $this->successResponse("Get review date", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function upgradeReview(Request $request)
    {
        $year = Carbon::now()->format("Y");

        if($request->review_date_id != null)
        {
            //upgrade
            $data = $this->queryReviewDate()->find($request->review_date_id);

            if(!$data) {
                return $this->errorResponse("Goals & Review Date not found", 404);
            }

            try {
                $data->year = $year;
                $data->month = $request->month ?? $data->month;
                $data->frequency_id = $request->frequency_id ?? $data->frequency_id;
                $data->is_include_review = $request->is_include_review ?? $data->is_include_review;
                $data->save();

                return $this->successResponse("Goals & Review date updated successfully", $data);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }

        }
        else {
            //created
            try {
                $data = ReviewsDate::create([
                    "month" => $request->month,
                    "year" => $year,
                    "frequency_id" => $request->frequency_id,
                    "is_include_review" => $request->is_include_review
                ]);

                return $this->successResponse("Goals & Review date created successfully", $data);

            }  catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }

        }
    }
}
