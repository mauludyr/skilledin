<?php

namespace Database\Seeders;

use App\Models\DurationTime;
use App\Models\ReviewsDate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DurationSeeder extends Seeder
{
    private function getDurations()
    {
        return collect([
            [
                "period_time_id" =>  1,
                "reporting_year" =>  "2022",
                "duration_start" => "2022-01-01",
                "duration_end" => "2022-03-31",
                "performance_start"=> "2022-09-20",
                "performance_end" => "2022-10-15"
            ],

            [
                "period_time_id" =>  2,
                "reporting_year" =>  "2022",
                "duration_start" => "2022-04-01",
                "duration_end" => "2022-06-30",
                "performance_start"=> "2022-09-20",
                "performance_end" => "2022-10-15"
            ],

            [
                "period_time_id" =>  3,
                "reporting_year" =>  "2022",
                "duration_start" => "2022-07-01",
                "duration_end" => "2022-09-30",
                "performance_start"=> "2022-09-20",
                "performance_end" => "2022-10-15"
            ],


            [
                "period_time_id" =>  4,
                "reporting_year" =>  "2022",
                "duration_start" => "2022-10-01",
                "duration_end" => "2022-12-31",
                "performance_start"=> "2022-09-20",
                "performance_end" => "2022-10-15"
            ],
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reviewDate = ReviewsDate::first();

        foreach ($this->getDurations() as $value) {
            $find = DurationTime::where('period_time_id', $value['period_time_id'])
                ->where('reporting_year', $value['reporting_year'])->first();

            if(!$find) {
                DurationTime::create([
                    "period_time_id" => $value["period_time_id"],
                    "review_date_id" => $reviewDate->id,
                    "duration_start" => Carbon::parse($value["duration_start"])->format('Y-m-d'),
                    "duration_end" => Carbon::parse($value["duration_end"])->format('Y-m-d'),
                    "performance_start" => Carbon::parse($value["performance_start"])->format('Y-m-d'),
                    "performance_end" => Carbon::parse($value["performance_end"])->format('Y-m-d'),
                    "reporting_year" => $value["reporting_year"],
                ]);
            }
        }
    }
}
