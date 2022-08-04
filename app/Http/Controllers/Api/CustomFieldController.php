<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\CustomFieldInterface;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    protected $customFieldInterface;

    public function __construct(CustomFieldInterface $customFieldInterface)
    {
        $this->customFieldInterface = $customFieldInterface;
    }

    public function showAll() {
        return $this->customFieldInterface->getAllCustomField();
    }

    public function showById($id)
    {
        return $this->customFieldInterface->findCustomFieldById($id);
    }

    public function ListDropdown($id)
    {
        return $this->customFieldInterface->findListDropdown($id);
    }


    public function store(Request $request)
    {
        return $this->customFieldInterface->saveCustomField($request);
    }

    public function update(Request $request)
    {
        return $this->customFieldInterface->updateCustomField($request);
    }

    public function destroy($id)
    {
        return $this->customFieldInterface->deleteCustomField($id);
    }
}
