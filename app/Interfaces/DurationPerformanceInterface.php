<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface DurationPerformanceInterface
{
    // Get All Duration & Performance Time Period Data
    public function getAllDurationPerformance();

    // Find Duration & Performance Time Period Data By ID
    public function findDurationPerformanceById($id);

    // Save Duration & Performance Time Period Data
    public function saveDurationPerformance(Request $request);

    // Update Duration & Performance Time Period Data
    public function updateDurationPerformance(Request $request, $id);


    // Delete Duration & Performance Time Period Data
    public function deleteDurationPerformance($id);
}
