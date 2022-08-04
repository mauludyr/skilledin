<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface CustomTypeInterface
{
    public function getAllCustomType();
    public function findCustomTypeById($gradeId);
    public function saveCustomType(Request $request);
    public function updateCustomType(Request $request, $id);
    public function deleteCustomType($gradeId);
}
