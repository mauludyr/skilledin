<?php

namespace Database\Seeders;

use App\Models\OkrPotential;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OkrPotentialSeeder extends Seeder
{
    private function getPotentials()
    {
        return collect([
            'Stars',
            'Core Players',
            'Inconsistent Players',
            'High Potential',
            'Solid Performers',
            'Average Performers',
            'High Performers',
            'Potential Gems',
            'Risk'
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getPotentials() as $value) {
            $name = ucwords(trim($value));
            $slug = Str::slug(Str::lower($name));
            $findPotential = OkrPotential::where("potential_slug", $slug)->first();
            if(!$findPotential) {
                OkrPotential::create([
                    'potential_name' => $name,
                    'potential_slug' => $slug
                ]);
            }


        }
    }
}
