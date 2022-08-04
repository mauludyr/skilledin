<?php

namespace Database\Seeders;

use App\Models\ProfileFieldSetting;
use App\Models\ProfileSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProfileSettingSeeder extends Seeder
{

    private function getCollectionData()
    {
        return collect([
            [
                "name" => "Personal",
                "list" => [
                    "First Name",
                    "Middle Name",
                    "Birthday",
                    "Pronouns",
                    "Nationality",
                    "Address",
                    "Personal Email",
                    "Personal Mobile",
                    "Emergency Contact Name",
                    "Emergency Contact Number"
                ]
            ],
            [
                "name" => "Work",
                "list" => [
                    "Role ID",
                    "Grade",
                    "Salary",
                    "Employment Type",
                    "Employment Contract",
                    "Direct Manager",
                    "Dotted Line Manager",
                    "Direct Reports",
                    "Dotted Line Reports",
                    "Mobility History"
                ]
            ],
            [
                "name" => "Skills",
                "list" => [
                    "Role Competencies",
                    "Leadership Profile",
                    "Remote Collaboration"
                ]
            ],
            [
                "name" => "Goals",
                "list" => [
                    "Objective Feed"
                ]
            ],
            [
                "name" => "Development",
                "list" => []
            ],
            [
                "name" => "Performance",
                "list" => [
                    "Performance Review"
                ]
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

        foreach ($this->getCollectionData() as $value) {
            $name = ucwords($value['name']);
            $slug = Str::slug(Str::lower($name));

            $profileSetting = ProfileSetting::where('slug', $slug)->first();

            if(!$profileSetting) {
                $profileSetting = ProfileSetting::create([
                    "name" => $name,
                    "slug" => $slug
                ]);
            }

            if($profileSetting->profileFieldSetting()->count() <= 0)
            {
                if(count($value["list"]) > 0) {
                    foreach ($value["list"] as $list) {
                        $name = ucwords(Str::lower($list));
                        $slug = Str::slug(Str::lower($name));

                        ProfileFieldSetting::create([
                            "field_name" => $name,
                            "field_slug" => $slug,
                            "profile_setting_id" => $profileSetting->id
                        ]);
                    }
                }
            }
        }
    }
}
