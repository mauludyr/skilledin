<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\MeasureInterface;
use Illuminate\Http\Request;

class MeasureController extends Controller
{
    protected $measureInterface;

    public function __construct(MeasureInterface $interfaces)
    {
        $this->measureInterface = $interfaces;
    }

    public function showAll() {
        return $this->measureInterface->getAllMeasures();
    }

    public function showById($id)
    {
        return $this->measureInterface->findMeasureById($id);
    }

    public function showBySlug($slug)
    {
        return $this->measureInterface->findMeasureBySlug($slug);
    }


    public function store(Request $request)
    {
        return $this->measureInterface->saveMeasure($request);
    }

    public function update(Request $request, $id)
    {
        return $this->measureInterface->updateMeasure($request, $id);
    }
}
