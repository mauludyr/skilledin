<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CustomFieldInterface
{
    public function getAllCustomField();
    public function findCustomFieldById($id);
    public function findListDropdown($id);
    public function saveCustomField(Request $request);
    public function updateCustomField(Request $request);
    public function deleteCustomField($id);
}
