<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface NationalityInterface
{
    public function findNationalityById($nationalityId);
    public function findNationalityByCode($nationalityCode);
    public function saveNationality(Request $request);
    public function updateNationality(Request $request, $id);
    public function getAllNationality();
    public function deleteNationality($nationalityId);
}
