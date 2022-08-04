<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface JobPositionInterface
{
    public function getAllJobPosition();
    public function findJobPositionById($id);
    public function findJobPositionBySlug($slug);
    public function saveJobPosition(Request $request);
    public function updateJobPosition(Request $request, $id);
    public function deleteJobPosition($id);
}
