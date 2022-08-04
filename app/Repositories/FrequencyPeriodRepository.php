<?php

namespace App\Repositories;

use App\Interfaces\FrequencyPeriodInterface;
use App\Models\FrequencyPeriod;
use App\Traits\ResponseAPI;
use Exception;

class FrequencyPeriodRepository implements FrequencyPeriodInterface
{
    use ResponseAPI;

    private function queryFrequency()
    {
        return FrequencyPeriod::with([
            "reviewDates"
        ]);
    }

    public function getAllFrequency()
    {
        try {
            $data = $this->queryFrequency()->orderBy("id", "asc")->get()->makeHidden([
                "created_at", "updated_at"
            ]);
            return $this->successResponse("Get all frequency of periods", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
