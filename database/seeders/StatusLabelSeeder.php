<?php

namespace Database\Seeders;

use App\Models\TaskLabel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusLabelSeeder extends Seeder
{
    protected function getStatus()
    {
        return collect([
            "social media",
            "on boarding"
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

            $findStatus = TaskLabel::where("label_slug", $slug)->first();

            if(!$findStatus) {
                TaskLabel::create([
                    "label_name" => $name,
                    "label_slug" => $slug
                ]);
            }


        }
    }
}
