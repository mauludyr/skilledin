<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeesImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        return User::with([
            "systemStatus",
            "profile",
            "employment",
            "customFields"
        ])->all();
    }
}
