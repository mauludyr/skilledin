<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface KeyResultInterface
{
    // Show All Key Result By Auth User
    public function getAllKeyResultByAuthUser();

    // Find Key Result By ID
    public function findKeyResultById($id);

    // Create new Key Result
    public function saveKeyResult(Request $request);

    // Update Key Result by Id
    public function updateKeyResult(Request $request, $id);



}
