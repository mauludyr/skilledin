<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\CustomTypeInterface;
use Illuminate\Http\Request;

class CustomTypeController extends Controller
{
    protected $customTypeInterface;

    public function __construct(CustomTypeInterface $customTypeInterface)
    {
        $this->customTypeInterface = $customTypeInterface;
    }

    public function showAll() {
        return $this->customTypeInterface->getAllCustomType();
    }

    public function showById($id)
    {
        return $this->customTypeInterface->findCustomTypeById($id);
    }

    public function store(Request $request)
    {
        return $this->customTypeInterface->saveCustomType($request);
    }

    public function update(Request $request, $id)
    {
        return $this->customTypeInterface->updateCustomType($request, $id);
    }

    public function destroy($id)
    {
        return $this->customTypeInterface->deleteCustomType($id);
    }
}
