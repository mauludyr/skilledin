<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\CustomParamInterface;
use Illuminate\Http\Request;

class CustomParamController extends Controller
{
    protected $customParamInterface;

    public function __construct(CustomParamInterface $customParamInterface)
    {
        $this->customParamInterface = $customParamInterface;
    }

    public function showAll() {
        return $this->customParamInterface->getAllCustomParam();
    }

    public function showById($id)
    {
        return $this->customParamInterface->findCustomParamById($id);
    }


    public function showBySlug($slug)
    {
        return $this->customParamInterface->findCustomParamBySlug($slug);
    }

    public function store(Request $request)
    {
        return $this->customParamInterface->saveCustomParam($request);
    }

    public function update(Request $request, $id)
    {
        return $this->customParamInterface->updateCustomParam($request, $id);
    }

    public function destroy($id)
    {
        return $this->customParamInterface->deleteCustomParam($id);
    }
}
