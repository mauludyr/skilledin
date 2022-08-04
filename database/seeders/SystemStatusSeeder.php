<?php

namespace Database\Seeders;

use App\Models\SystemStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SystemStatusSeeder extends Seeder
{
    private function getStatus()
    {
        return collect([
            "activated",
            "pending",
            "error",
            "deactivated"
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
            $slug = Str::slug(Str::lower(trim($value)));

            $find = SystemStatus::where("slug", $slug)->first();

            if(!$find) {
                SystemStatus::create([
                    'name' => ucwords($value),
                    'slug' => $slug
                ]);
            }

        }
    }
}
