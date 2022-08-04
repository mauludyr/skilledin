<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UserManagement
{
    public function combineToFullname($firstName, $middleName = '', $lastName = '')
    {
        if(empty($firstName)) return "No Name";

        $firstName = Str::ucfirst(Str::lower($firstName));

        $middleName = (!empty($middleName) && $middleName != null) ? ' '. Str::ucfirst(Str::lower($middleName)) : '';

        $lastName = (!empty($lastName) && $lastName != null) ? ' '. Str::ucfirst(Str::lower($lastName)) : '';

        return trim($firstName.''. $middleName.''.$lastName);
    }
}
