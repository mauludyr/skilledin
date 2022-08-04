<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PeriodTimeInterface
{
    public function getAllPeriodByReviewDate();

    // Get All Period Data
    public function getAllPeriod();

    // Save Period Data
    public function savePeriod(Request $request);

    // Update Period Data
    public function updatePeriod(Request $request, $id);

    // Delete Period Data
    public function deletePeriod($id);
}
