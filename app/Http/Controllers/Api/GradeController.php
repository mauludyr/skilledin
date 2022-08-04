<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\GradeInterface;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected $gradeInterface;

    public function __construct(GradeInterface $gradeInterface)
    {
        $this->gradeInterface = $gradeInterface;
    }


    public function showAll() {
        return $this->gradeInterface->getAllGrade();
    }

    public function showById($id)
    {
        return $this->gradeInterface->findGradeById($id);
    }


    public function showBySlug($slug)
    {
        return $this->gradeInterface->findGradeBySlug($slug);
    }

    public function store(Request $request)
    {
        return $this->gradeInterface->saveGrade($request);
    }

    public function update(Request $request, $id)
    {
        return $this->gradeInterface->updateGrade($request, $id);
    }

    public function destroy($id)
    {
        return $this->gradeInterface->deleteGrade($id);
    }
}
