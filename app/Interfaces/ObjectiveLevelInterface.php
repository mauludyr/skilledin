<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ObjectiveLevelInterface
{
    public function getAllObjectiveLevel();
    public function findObjectiveLevelById($id);
    public function findObjectiveLevelBySlug($slug);
    public function saveObjectiveLevel(Request $request);
    public function updateObjectiveLevel(Request $request, $id);
}
