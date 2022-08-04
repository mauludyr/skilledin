<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface CustomParamInterface
{
    public function getAllCustomParam();
    public function findCustomParamById($id);
    public function findCustomParamBySlug($slug);
    public function saveCustomParam(Request $request);
    public function updateCustomParam(Request $request, $id);
    public function deleteCustomParam($id);
}
