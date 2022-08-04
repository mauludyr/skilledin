<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{

    public function permissions()
    {
        return collect([
            'salary',
            'skills',
            'development',
            'performance',
            'add new employee',
            'deactive employee',
            'change salary',
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->permissions() as $value) {
            $name = Str::slug(strtolower($value), '-');
            $permission = Permission::where('name', $name)->first();

            if(!$permission) {
                Permission::create([
                    "name" => Str::slug(strtolower($value), '-'),
                ]);
            }
        }
    }
}
