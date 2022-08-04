<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OKRInterface
{

    // Get All Key Result By Objective ID
    public function getAllKeyResultByObjectiveId($id);


    // Create new Objective with Key Result
    public function saveObjectiveKeyResult(Request $request);


    // Check in Key Result Objective
    public function checkInOKRByAuthUser(Request $req);


    // Get All Key Result Tracking Of User
    public function getAllKeyResultTrackingUser();

    // Get All Objective Key Result Progression
    public function getAllHistoryKeyResultByObjective();

    // Get All Key Result Tracking With Objective ID
    public function getObjectiveKeyResultProgression();

    // Update OKR Levels
    public function updateOkrLevel(Request $request);

}
