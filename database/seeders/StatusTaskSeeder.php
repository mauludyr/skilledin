<?php

namespace Database\Seeders;

use App\Models\StatusTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusTaskSeeder extends Seeder
{
    protected function getStatus()
    {
        return collect([
            "Open",
            "Scheduled",
            "Complete"
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getStatus() as $value) {
            $slug = Str::slug($value);
            $findStatus = StatusTask::where("status_slug", $slug)->first();

            if(!$findStatus) {
                StatusTask::create([
                    "status_name" => $value,
                    "status_slug" => $slug
                ]);
            }


        }
    }
}
