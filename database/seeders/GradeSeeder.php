<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GradeSeeder extends Seeder
{
    private function getGrades()
    {
        return collect([
            "junior",
            "middle",
            "professional",
            "senior",
            "exceptional",
            "luminary"
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getGrades() as $value) {
            $slug = Str::slug(Str::lower($value));
            $name = ucwords(Str::lower($value));

            $findGrade = Grade::where("grade_slug", $slug)->first();
            if(!$findGrade)
            {
                Grade::create([
                    'grade_name' => $name,
                    'grade_slug' => $slug,
                ]);
            }
        }
    }
}
