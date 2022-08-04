<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ObjectiveLevelInterface;
use Illuminate\Http\Request;

class ObjectiveLevelController extends Controller
{
    protected $objectiveInterface;

    public function __construct(ObjectiveLevelInterface $interfaces)
    {
        $this->objectiveInterface = $interfaces;
    }

    public function showAll() {
        return $this->objectiveInterface->getAllObjectiveLevel();
    }

    public function showById($id)
    {
        return $this->objectiveInterface->findObjectiveLevelById($id);
    }

    public function showBySlug($slug)
    {
        return $this->objectiveInterface->findObjectiveLevelBySlug($slug);
    }


    public function store(Request $request)
    {
        return $this->objectiveInterface->saveObjectiveLevel($request);
    }

    public function update(Request $request, $id)
    {
        return $this->objectiveInterface->updateObjectiveLevel($request, $id);
    }
}
