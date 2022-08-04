<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\CustomField;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    private function getCustom()
    {
        return [
            ["field_param_id"=> 1, "field_type_id"=> 1],
            ["field_param_id"=> 2, "field_type_id"=> 1],
            ["field_param_id"=> 3, "field_type_id"=> 9],
            ["field_param_id"=> 4, "field_type_id"=> 1],
            ["field_param_id"=> 5, "field_type_id"=> 6],
            ["field_param_id"=> 6, "field_type_id"=> 2],
            ["field_param_id"=> 7, "field_type_id"=> 2],
            ["field_param_id"=> 8, "field_type_id"=> 2],
            ["field_param_id"=> 9, "field_type_id"=> 10],
            ["field_param_id"=> 10, "field_type_id"=> 7],
            ["field_param_id"=> 11, "field_type_id"=> 5],
            ["field_param_id"=> 12, "field_type_id"=> 8]
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
            $findDataField = CustomField::where("field_param_id", $value["field_param_id"])->first();
            if(!$findDataField){
                CustomField::create([
                    'field_param_id' => $value["field_param_id"],
                    'field_type_id'=> $value["field_type_id"]
                ]);
            }
        }
    }
}
