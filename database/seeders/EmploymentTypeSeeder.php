<?php

namespace Database\Seeders;

use App\Models\EmploymentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmploymentTypeSeeder extends Seeder
{
    private function getTypes()
    {
        return collect([
            "Fulltime",
            "Parttime",
            "Contractor",
            "Internship",
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
            $name = ucwords(trim($value));
            $slug = Str::slug(Str::lower($name));

            $find = EmploymentType::where("emp_type_slug", $slug)->first();

            if(!$find) {
                EmploymentType::create([
                    "emp_type_name" => $name,
                    "emp_type_slug" => $slug,
                ]);
            }


        }
    }
}
