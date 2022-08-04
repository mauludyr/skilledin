<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ObjectiveInterface
{
    // Get All Objective
    public function getAllObjective();

    // Find Objective By ID
    public function findObjectiveById($id);

    // Create new Objective
    public function saveObjective(Request $request);

    // Update Objective by Id
    public function updateObjective(Request $request, $id);

    // Delete Objective
    public function deleteObjective($id);

    // Get All Parent Objective
    public function getAllParentObjective();

    // Set Parent Objective By Id
    public function setParentObjectiveByObjectiveId(Request $request, $id);


    public function getCountObjective();
}
