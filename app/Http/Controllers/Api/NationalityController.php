<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\NationalityInterface;
use Illuminate\Http\Request;

class NationalityController extends Controller
{
    protected $nationalityInterface;

    public function __construct(NationalityInterface $nationalityInterface)
    {
        $this->nationalityInterface = $nationalityInterface;
    }

    public function showAll() {
        return $this->nationalityInterface->getAllNationality();
    }

    public function showById($id)
    {
        return $this->nationalityInterface->findNationalityById($id);
    }


    public function showByCode($code)
    {
        return $this->nationalityInterface->findNationalityByCode($code);
    }

    public function store(Request $request)
    {
        return $this->nationalityInterface->saveNationality($request);
    }

    public function update(Request $request, $id)
    {
        return $this->nationalityInterface->updateNationality($request, $id);
    }

    public function destroy($id)
    {
        return $this->nationalityInterface->deleteNationality($id);
    }
}
