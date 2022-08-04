<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface LocationInterface
{
    public function findLocationById($id);
    public function findLocationByCode($code);
    public function saveLocation(Request $request);
    public function updateLocation(Request $request, $id);
    public function getAllLocation();
    public function deleteLocation($id);
}
