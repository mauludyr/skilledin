<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait EmployeeFileManagement
{
    public function getFieldCompareFromFileRowWithDatabaseColumn($data)
    {
        $columns = [];

        foreach ($data as $value) {
            $lower = Str::lower($value);

            switch ($lower) {
                case 'employee id':
                case 'no identity':
                    array_push($columns, "identity");
                    break;

                case 'role access':
                case 'role name':
                    array_push($columns, "role_name");
                    break;

                case 'email':
                    array_push($columns, "email");
                    break;

                case 'first name':
                case 'firstname':
                    array_push($columns, "first_name");
                    break;

                case 'middle name':
                case 'middlename':
                    array_push($columns, "middle_name");
                    break;

                case 'last name':
                case 'lastname':
                    array_push($columns, "last_name");
                    break;

                case 'birthday':
                    array_push($columns, "birthday");
                    break;

                case 'gender':
                    array_push($columns, "gender");
                    break;

                case 'pronouns':
                    array_push($columns, "pronouns");
                    break;

                case 'superpower':
                    array_push($columns, "superpower");
                    break;

                case 'address':
                    array_push($columns, "address");
                    break;

                case 'phone number':
                case 'phone':
                    array_push($columns, "phone_number");
                    break;

                case 'emergency contact name':
                    array_push($columns, "emergency_contact_name");
                    break;

                case 'emergency contact number':
                    array_push($columns, "emergency_contact_number");
                    break;

                case 'date joined':
                    array_push($columns, "date_joined");
                    break;

                case 'nationality':
                    array_push($columns, "nationality");
                    break;

                case 'location':
                    array_push($columns, "location");
                    break;

                default:
                    array_push($columns, str_replace(' ','_',$lower));
                    break;

            }

        }

        return $columns;
    }

}
