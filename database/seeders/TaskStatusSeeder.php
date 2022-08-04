<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskStatusSeeder extends Seeder
{
    protected function getStatus()
    {
        return collect([
            "on track",
            "off track",
            "at risk"
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
            $name = Str::upper(trim($value));
            $slug = Str::slug($name);

            $findStatus = TaskStatus::where("status_slug", $slug)->first();

            if(!$findStatus) {
                TaskStatus::create([
                    "status_name" => $name,
                    "status_slug" => $slug
                ]);
            }


        }
    }
}
