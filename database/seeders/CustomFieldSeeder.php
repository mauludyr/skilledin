<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\CustomFieldParams;

class CustomFieldSeeder extends Seeder
{

    private function getCustom()
    {
        return [
            ["name"=> 'First Name', "description"=> 'First Name'],
            ["name"=> 'Last Name', "description"=> 'Last Name'],
            ["name"=> 'Manager Name', "description"=> 'Manager Name'],
            ["name"=> 'Role Name', "description"=> 'Role Name'],
            ["name"=> 'Role ID', "description"=> 'Role ID'],
            ["name"=> 'Grade', "description"=> 'Grade'],
            ["name"=> 'Department', "description"=> 'Department'],
            ["name"=> 'Employment Type', "description"=> 'Employment Type'],
            ["name"=> 'Salary', "description"=> 'Salary'],
            ["name"=> 'Location', "description"=> 'Location'],
            ["name"=> 'Start Date', "description"=> 'Start Date'],
            ["name"=> 'Company Email', "description"=> 'Company Email']
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
            $name = ucwords(trim($value["name"]));
            $findDataField = CustomFieldParams::where("name", $value["name"])->first();
            if(!$findDataField){
                CustomFieldParams::create([
                    'name' => $name,
                    'slug'=> Str::slug(Str::lower($value["name"]))
                ]);
            }
        }
    }
}
