<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserCustomInterface
{
    public function saveUserCustomField(Request $request);
    public function updateUserCustomField(Request $request);
    public function deleteUserCustomField($id);
}
