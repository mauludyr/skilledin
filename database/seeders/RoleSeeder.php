<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            "HR Admin",
            "Human Resources",
            "Manager",
            "Employee"
        ];

        foreach ($roles as $value) {
            $name = Str::lower($value);
            $findRole = Role::where('name', $name)->first();

            if(!$findRole) {
                $role = Role::create([
                    "name" => Str::lower($value)
                ]);
            }


        }
    }
}
