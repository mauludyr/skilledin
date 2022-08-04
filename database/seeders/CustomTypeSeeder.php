<?php

namespace Database\Seeders;

use App\Models\CustomFieldType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomTypeSeeder extends Seeder
{
    private function getCustomTypes()
    {
        return collect([
            'Text',
            'Dropdown',
            'File',
            'Image',
            'Date',
            'Numerical',
            'Location',
            'Email',
            'Link',
            'Currency'
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getCustomTypes() as $value) {
            CustomFieldType::create([
                "name" => $value
            ]);
        }
    }
}
