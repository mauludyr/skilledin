<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\LocationInterface;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $locationInterface;

    public function __construct(LocationInterface $locationInterface)
    {
        $this->locationInterface = $locationInterface;
    }

    public function showAll() {
        return $this->locationInterface->getAllLocation();
    }

    public function showById($id)
    {
        return $this->locationInterface->findLocationById($id);
    }


    public function showByCode($code)
    {
        return $this->locationInterface->findLocationByCode($code);
    }

    public function store(Request $request)
    {
        return $this->locationInterface->saveLocation($request);
    }

    public function update(Request $request, $id)
    {
        return $this->locationInterface->updateLocation($request, $id);
    }

    public function destroy($id)
    {
        return $this->locationInterface->deleteLocation($id);
    }
}
