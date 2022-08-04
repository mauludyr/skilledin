<?php

namespace Database\Seeders;

use App\Models\ObjectiveLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ObjectiveLevelSeeder extends Seeder
{
    private function getLevels()
    {
        return [
            ["name"=> 'Company', "score"=> 1],
            ["name"=> 'Departement', "score"=> 2],
            ["name"=> 'Team', "score"=> 3],
            ["name"=> 'Individual', "score"=> 4]
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getLevels() as $value) {
            $name = ucwords(trim($value['name']));
            $slug = Str::slug(Str::lower($name));
            $findLevel = ObjectiveLevel::where("level_slug", $slug)->first();
            if(!$findLevel) {
                ObjectiveLevel::create([
                    'level_name' => $name,
                    'level_slug' => $slug,
                    'level_value' => $value['score']
                ]);
            }


        }
    }
}
