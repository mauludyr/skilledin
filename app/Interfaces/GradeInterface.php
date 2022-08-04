<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface GradeInterface
{
    public function findGradeById($gradeId);
    public function findGradeBySlug($gradeSlug);
    public function saveGrade(Request $request);
    public function updateGrade(Request $request, $id);
    public function getAllGrade();
    public function deleteGrade($gradeId);
}
