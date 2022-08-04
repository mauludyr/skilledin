<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReviewerGroup;

class ReviewerGroupSeeder extends Seeder
{
    private function getReviewerGroups()
    {
        return collect([
            "SELF",
            "DIRECT MANAGER",
            "DOTTED LINE MANAGER",
            "PEERS",
            "REVERSE REVIEW",
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getReviewerGroups() as $value) {
            ReviewerGroup::create([
                "name" => $value
            ]);
        }
    }
}
