<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\OkrPotentialInterface;
use Illuminate\Http\Request;

class OkrPotentialController extends Controller
{
    protected $objectiveInterface;

    public function __construct(OkrPotentialInterface $interfaces)
    {
        $this->objectiveInterface = $interfaces;
    }

    public function showAll() {
        return $this->objectiveInterface->getAllOkrPotential();
    }

    public function showById($id)
    {
        return $this->objectiveInterface->findOkrPotentialById($id);
    }

    public function showBySlug($slug)
    {
        return $this->objectiveInterface->findOkrPotentialBySlug($slug);
    }


    public function store(Request $request)
    {
        return $this->objectiveInterface->saveOkrPotential($request);
    }

    public function update(Request $request, $id)
    {
        return $this->objectiveInterface->updateOkrPotential($request, $id);
    }
}
