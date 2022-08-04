<?php

namespace Database\Seeders;

use App\Models\Visibility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisibilitySeeder extends Seeder
{
    private function getTypes()
    {
        return collect([
            [
                "role_id" => 1, 
                "name" => "Salary", 
                "is_exclude" => true
            ],

            [
                "role_id" => 1, 
                "name" => "Skills", 
                "is_exclude" => true
            ],

            [
                "role_id" => 1, 
                "name" => "Development", 
                "is_exclude" => true
            ],

            [
                "role_id" => 1, 
                "name" => "Performance", 
                "is_exclude" => true
            ],

            [
                "role_id" => 1, 
                "name" => "Add New Employee", 
                "is_exclude" => false
            ],

            [
                "role_id" => 1, 
                "name" => "Deactivate Employee", 
                "is_exclude" => false
            ],

            [
                "role_id" => 1, 
                "name" => "Change Salary", 
                "is_exclude" => false
            ],

            [
                "role_id" => 2, 
                "name" => "Salary", 
                "is_exclude" => true
            ],

            [
                "role_id" => 2, 
                "name" => "Skills", 
                "is_exclude" => true
            ],

            [
                "role_id" => 2, 
                "name" => "Development", 
                "is_exclude" => true
            ],

            [
                "role_id" => 2, 
                "name" => "Performance", 
                "is_exclude" => true
            ],

            [
                "role_id" => 2, 
                "name" => "Add New Employee", 
                "is_exclude" => false
            ],

            [
                "role_id" => 2, 
                "name" => "Deactivate Employee", 
                "is_exclude" => false
            ],

            [
                "role_id" => 2, 
                "name" => "Change Salary", 
                "is_exclude" => false
            ],

            [
                "role_id" => 3, 
                "name" => "Salary", 
                "is_exclude" => true
            ],

            [
                "role_id" => 3, 
                "name" => "Skills", 
                "is_exclude" => true
            ],

            [
                "role_id" => 3, 
                "name" => "Development", 
                "is_exclude" => true
            ],

            [
                "role_id" => 3, 
                "name" => "Performance", 
                "is_exclude" => true
            ],

            [
                "role_id" => 3, 
                "name" => "Add New Employee", 
                "is_exclude" => false
            ],

            [
                "role_id" => 3, 
                "name" => "Deactivate Employee", 
                "is_exclude" => false
            ],

            [
                "role_id" => 3, 
                "name" => "Change Salary", 
                "is_exclude" => false
            ],

            [
                "role_id" => 4, 
                "name" => "Salary", 
                "is_exclude" => true
            ],

            [
                "role_id" => 4, 
                "name" => "Skills", 
                "is_exclude" => true
            ],

            [
                "role_id" => 4, 
                "name" => "Development", 
                "is_exclude" => true
            ],

            [
                "role_id" => 4, 
                "name" => "Performance", 
                "is_exclude" => true
            ],

            [
                "role_id" => 4, 
                "name" => "Add New Employee", 
                "is_exclude" => false
            ],

            [
                "role_id" => 4, 
                "name" => "Deactivate Employee", 
                "is_exclude" => false
            ],

            [
                "role_id" => 4, 
                "name" => "Change Salary", 
                "is_exclude" => false
            ],
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getTypes() as $value) {
            $name = ucwords(trim($value['name']));
            $slug = Str::slug(Str::lower($name));
            $find = Visibility::where("role_id", 5)->first();
            if(!$find) {
                Visibility::create([
                    "role_id" => $value['role_id'],
                    "name" => $name,
                    "slug" => $slug,
                    "is_exclude" => $value['is_exclude'],
                ]);
            }


        }
    }
}
