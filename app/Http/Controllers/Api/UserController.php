<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userInterface;

    public function __construct(UserInterface $interfaces)
    {
        $this->userInterface = $interfaces;
    }

    public function showAllUser()
    {
        return $this->userInterface->getAllUser();
    }

    public function showUser() {
        return $this->userInterface->getUserAuth();
    }

    public function showUserAdmin() {
        return $this->userInterface->getUserAdmin();
    }

    public function showById($id)
    {
        return $this->userInterface->getUserById($id);
    }

    public function storeUser(Request $request)
    {

        return $this->userInterface->saveUser($request);
    }

    public function storeUserRole(Request $request)
    {

        return $this->userInterface->changeUserRole($request);
    }


    public function importFileEmployee(Request $request)
    {
        return $this->userInterface->importFileExcel($request);
    }

    public function importFileEmployeeSave(Request $request)
    {
        return $this->userInterface->importFileExcelSave($request);
    }

    public function downloadEmployeeTemplate($name)
    {
        return $this->userInterface->downloadEmployeeTemplate($name);
    }


}
