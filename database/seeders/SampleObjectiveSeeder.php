<?php

namespace Database\Seeders;

use App\Models\KeyResultObjective;
use App\Models\Objective;
use Illuminate\Database\Seeder;

class SampleObjectiveSeeder extends Seeder
{
    private function getOKRs()
    {
        $path = public_path() . "/data.json";
        return json_decode(file_get_contents($path), true);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $okrs = $this->getOKRs();

        foreach ($okrs["objectives"] as $value) {
            Objective::create($value);
        }

        foreach ($okrs["key_results"] as $value) {
            KeyResultObjective::create($value);
        }
    }
}
