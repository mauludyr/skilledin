<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\HumanResourceInterface;
use Illuminate\Http\Request;

class HumanResourceController extends Controller
{
    protected $hrInterface;

    public function __construct(HumanResourceInterface $interface)
    {
        $this->hrInterface = $interface;
    }

    public function showAllHumanResourceTeams()
    {
        return $this->hrInterface->getAllHumanResourceTeams();
    }

    public function storeHumanResource(Request $request)
    {
        return $this->hrInterface->saveHumanResource($request);
    }

    public function updateHumanResource(Request $request, $id)
    {
        return $this->hrInterface->updateHumanResource($request, $id);
    }
}
