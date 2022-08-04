<?php

namespace Database\Seeders;

use App\Models\FrequencyPeriod;
use Illuminate\Database\Seeder;
use App\Models\PeriodTime;
use App\Models\ReviewsDate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PeriodSeeder extends Seeder
{
    private function getFrequencies()
    {
        return collect([
            "Quarterly",
            "Half Year",
            "Annual Only"
        ]);
    }

    private function getPeriods()
    {
        return collect([
            "Q1",
            "Q2",
            "Q3",
            "Q4",
            "H1",
            "H2",
            "FY"
        ]);
    }




    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        foreach ($this->getFrequencies() as $value) {
            $name = ucwords(Str::lower($value));
            $slug = Str::slug(Str::lower($value));

            $findFrequency = FrequencyPeriod::where('slug', $slug)->first();
            if(!$findFrequency) {
                $findFrequency = FrequencyPeriod::create([
                    "name" => $name,
                    "slug" => $slug,
                ]);
            }
        }


        $reviewDate = ReviewsDate::first();

        if(!$reviewDate) {
            $reviewDate = ReviewsDate::create([
                "month"=> "January",
                "year" => Carbon::now()->format("Y"),
                "frequency_id"=>  FrequencyPeriod::where('slug', 'quarterly')->first()->id
            ]);
        }

        foreach ($this->getPeriods() as $value) {
            $slug = Str::slug(Str::lower($value));
            $findPeriod = PeriodTime::where('period_slug', $slug)->first();

            if(!$findPeriod) {
                PeriodTime::create([
                    "period_name" => Str::upper($value),
                    "period_slug" => $slug,
                ]);
            }
        }


    }
}
