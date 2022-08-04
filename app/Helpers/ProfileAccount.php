<?php

namespace App\Helpers;

use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileAccount
{

    public function getAccountColumnListing()
    {
        $collections = [
            [
                "column_name" => "personal_email",
                "text" => "Personal Email",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "personal_email",
            ],

            [
                "column_name" => "first_name",
                "text" => "First Name",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "first_name",
            ],

            [
                "column_name" => "middle_name",
                "text" => "Middle Name",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "middle_name",
            ],

            [
                "column_name" => "last_name",
                "text" => "Last Name",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "last_name",
            ],

            [
                "column_name" => "role_id",
                "text" => "Role ID",
                "type" => "text",
                "db_table_name" => "job_positions",
                "db_column_name" => "job_code",
            ],

            [
                "column_name" => "birthday",
                "text" => "Birthday",
                "type" => "date",
                "db_table_name" => "profiles",
                "db_column_name" => "birthday",
            ],

            [
                "column_name" => "address",
                "text" => "Address",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "address",
            ],

            [
                "column_name" => "personal_mobile",
                "text" => "Personal Mobile",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "phone_number",
            ],

            [
                "column_name" => "emergency_contact_name",
                "text" => "Emergency Contact Name",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "emergency_contact_name",
            ],

            [
                "column_name" => "emergency_contact_number",
                "text" => "Emergency Contact Number",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "emergency_contact_number",
            ],

            [
                "column_name" => "start_date",
                "text" => "Start Date",
                "type" => "date",
                "db_table_name" => "profiles",
                "db_column_name" => "date_joined",
            ],


            [
                "column_name" => "nationality",
                "text" => "Nationality",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "nationality_id",
            ],

            [
                "column_name" => "location",
                "text" => "Location",
                "type" => "text",
                "db_table_name" => "profiles",
                "db_column_name" => "location_id",
            ],

            [
                "column_name" => "salary",
                "text" => "Salary",
                "type" => "text",
                "db_table_name" => "employments",
                "db_column_name" => "salary_id",
            ],

        ];

        $customFields = CustomField::with(["customParam", "customType"])->get();

        foreach ($customFields as $item)
        {
            // array_push($collections, [
            //     "column_name" => $item->customParam->slug,
            //     "text" => $item->customParam->name,
            //     "type" => Str::lower($item->customType->name),
            //     "db_table_name" => "custom_field_params",
            //     "db_column_name" =>  $item->customParam->slug,
            // ]);
        }

        return collect($collections);
    }

}
