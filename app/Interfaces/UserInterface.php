<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserInterface
{
    public function getAllUser();
    public function getUserAuth();
    public function getUserAdmin();
    public function getUserById($id);
    public function saveUser(Request $request);
    public function changeUserRole(Request $request);
    public function importFileExcel(Request $request);
    public function importFileExcelSave(Request $request);
    public function downloadEmployeeTemplate($name);
}
