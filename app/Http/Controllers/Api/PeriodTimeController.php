<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\PeriodTimeInterface;
use Illuminate\Http\Request;

class PeriodTimeController extends Controller
{
    protected $periodInterface;
    protected $frequencyInterface;

    public function __construct(PeriodTimeInterface $interfaces)
    {
        $this->periodInterface = $interfaces;
    }

    //Show All Period By Review Date
    public function showAllPeriodReview()
    {
        return $this->periodInterface->getAllPeriodByReviewDate();
    }


    // Show All Period
    public function showAllPeriod()
    {
        return $this->periodInterface->getAllPeriod();
    }

    // Store Period
    public function storePeriod(Request $request)
    {
        return $this->periodInterface->savePeriod($request);
    }

    // Update Period
    public function updatePeriod(Request $request, $id)
    {
        return $this->periodInterface->updatePeriod($request, $id);
    }


    // Delete Period
    public function deletePeriod($id)
    {
        return $this->periodInterface->deletePeriod($id);
    }
}
