<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\DurationPerformanceInterface;
use Illuminate\Http\Request;

class DurationPerformanceController extends Controller
{
    protected $durationInterface;

    public function __construct(DurationPerformanceInterface $interfaces)
    {
        $this->durationInterface = $interfaces;
    }

    // Get All Duration & Performance Time Period Data
    public function showAllDurationPerformance()
    {
        return $this->durationInterface->getAllDurationPerformance();
    }

    // Find Duration & Performance Time Period Data By ID
    public function showDurationPerformanceById($id)
    {
        return $this->durationInterface->findDurationPerformanceById($id);
    }

    // Save Duration & Performance Time Period Data
    public function storeDurationPerformance(Request $request)
    {
        return $this->durationInterface->saveDurationPerformance($request);
    }

    // Update Duration & Performance Time Period Data
    public function updateDurationPerformance(Request $request, $id)
    {
        return $this->durationInterface->updateDurationPerformance($request, $id);
    }

    // Delete Duration & Performance Time Period Data
    public function deleteDurationPerformance($id)
    {
        return $this->durationInterface->deleteDurationPerformance($id);
    }

}
