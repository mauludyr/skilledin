<?php

namespace Database\Seeders;

use App\Models\Measure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MeasureSeeder extends Seeder
{

    protected function getMeasures()
    {
        return collect([
            'Number',
            'True/False',
            'Percent',
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getMeasures() as $value) {
            $name = trim($value);
            $slug = Str::slug(Str::lower($name));
            $findMeasure = Measure::where("measure_slug", $slug)->first();

            if(!$findMeasure)
            {
                Measure::create([
                    'measure_name' => $name,
                    'measure_slug' => $slug
                ]);
            }

        }
    }
}
