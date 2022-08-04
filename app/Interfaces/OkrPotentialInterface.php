<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OkrPotentialInterface
{
    public function getAllOkrPotential();
    public function findOkrPotentialById($id);
    public function findOkrPotentialBySlug($slug);
    public function saveOkrPotential(Request $request);
    public function updateOkrPotential(Request $request, $id);
}
