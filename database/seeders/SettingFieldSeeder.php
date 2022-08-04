<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\SettingField;

class SettingFieldSeeder extends Seeder
{
    private function getCustom()
    {
        return [
            ["field_name"=> 'First Name', "alias_name"=> 'first_name', 'type' => 'Text'],
            ["field_name"=> 'Last Name', "alias_name"=> 'last_name', 'type' => 'Text'],
            ["field_name"=> 'Manager Name', "alias_name"=> 'manager_name', 'type' => 'Link'],
            ["field_name"=> 'Role Name', "alias_name"=> 'role_name', 'type' => 'Text'],
            ["field_name"=> 'Grade', "alias_name"=> 'grade_name', 'type' => 'Dropdown'],
            ["field_name"=> 'Department', "alias_name"=> 'departement_name', 'type' => 'Dropdown'],
            ["field_name"=> 'Employment Type', "alias_name"=> 'employment_type', 'type' => 'Dropdown'],
            ["field_name"=> 'Salary', "alias_name"=> 'salary', 'type' => 'Currency'],
            ["field_name"=> 'Location', "alias_name"=> 'location', 'type' => 'Location'],
            ["field_name"=> 'Join Date', "alias_name"=> 'date_joined', 'type' => 'Date'],
            ["field_name"=> 'Company Email', "alias_name"=> 'company_email', 'type' => 'Email']
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getCustom() as $value) {
            $findDataField = SettingField::where("field_name", $value["field_name"])->first();
            if(!$findDataField){
                SettingField::create([
                    'field_name' => $value["field_name"],
                    'alias_name' => $value["alias_name"],
                    'type' => $value["type"]
                ]);
            }
        }
    }
}
