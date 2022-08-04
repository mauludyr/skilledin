<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface MeasureInterface
{
    public function getAllMeasures();
    public function findMeasureById($id);
    public function findMeasureBySlug($slug);
    public function saveMeasure(Request $request);
    public function updateMeasure(Request $request, $id);
}


