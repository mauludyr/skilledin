<?php

namespace Database\Seeders;

use App\Models\DirectReport;
use App\Models\Employment;
use App\Models\EmploymentType;
use App\Models\Grade;
use App\Models\JobPosition;
use App\Models\Location;
use App\Models\Nationality;
use App\Models\Profile;
use App\Models\SystemStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    protected function getUsers()
    {
        return [
            // HR ADMIN
            [
                "auth" =>[
                    "email" => "superadmin@skilledin.io",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "George",
                    "middle_name" => null,
                    "last_name" => "Brian",
                    "birthday" => "1978-08-10",
                    "address" => "Amtech Building, 159 Sin Ming Rd, Singapura 575625",
                    "phone_number" => "082311289900",
                    "nationality" => "singaporean",
                    "location" => "SG",
                    "date_joined" => "2003-10-10"
                ],
                "role" => "hr admin",
                "employment" => [
                    "grade" => "luminary",
                    "position" => "chief-executive-officer-ceo",
                    "type" => "fulltime"
                ],
                "manager" => null,
                "dotline_manager" => null,
                "system_status" => "activated"
            ],


            // MANAGER
            [
                "auth" =>[
                    "email" => "samuel@skilledin.com",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Samuel",
                    "middle_name" => "Walter",
                    "last_name" => null,
                    "birthday" => "1987-01-02",
                    "address" => "150 Bedok North Street 2, Singapura 460130",
                    "phone_number" => "089849234345",
                    "nationality" => "singaporean",
                    "location" => "SG",
                    "date_joined" => "2010-11-01"
                ],
                "role" => "manager",
                "employment" => [
                    "grade" => "exceptional",
                    "position" => "human-resources-manager",
                    "type" => "fulltime"
                ],
                "manager" => null,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "firdaus@lumoshive.com",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Muhammad",
                    "middle_name" => "",
                    "last_name" => "Firdaus",
                    "birthday" => "",
                    "address" => "",
                    "phone_number" => "08283806169710",
                    "nationality" => "indonesian",
                    "location" => "ID",
                    "date_joined" => ""
                ],
                "role" => "manager",
                "employment" => [
                    "grade" => "exceptional",
                    "position" => "project-manager",
                    "type" => "fulltime"
                ],
                "manager" => null,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "manager@skilledin.com",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Tester",
                    "middle_name" => "Manager",
                    "last_name" => null,
                    "birthday" => "1987-01-02",
                    "address" => "150 Bedok North Street 2, Singapura 460130",
                    "phone_number" => "089849234345",
                    "nationality" => "singaporean",
                    "location" => "SG",
                    "date_joined" => "2010-11-01"
                ],
                "role" => "manager",
                "employment" => [
                    "grade" => "exceptional",
                    "position" => "human-resources-manager",
                    "type" => "fulltime"
                ],
                "manager" => null,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],


            // HUMAN RESOURCES
            [
                "auth" =>[
                    "email" => "demo@skilledin.io",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Demo",
                    "middle_name" => "User",
                    "last_name" => null,
                    "birthday" => "1987-01-02",
                    "address" => "150 Bedok North Street 2, Singapura 460130",
                    "phone_number" => "089849234345",
                    "nationality" => "singaporean",
                    "location" => "SG",
                    "date_joined" => "2010-11-01"
                ],
                "role" => "human resources",
                "employment" => [
                    "grade" => "exceptional",
                    "position" => "human-resources-manager",
                    "type" => "fulltime"
                ],
                "manager" => null,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],


            [
                "auth" =>[
                    "email" => "natashia@skilledin.io",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Natashia",
                    "middle_name" => null,
                    "last_name" => null,
                    "birthday" => null,
                    "address" => null,
                    "phone_number" => null,
                    "nationality" => "singaporean",
                    "location" => "SG",
                    "date_joined" => null
                ],
                "role" => "human resources",
                "employment" => [
                    "grade" => "exceptional",
                    "position" => "human-resources-manager",
                    "type" => "fulltime"
                ],
                "manager" => null,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],


            // EMPLOYEE
            [
                "auth" =>[
                    "email" => "razor@skilledin.io",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Razor",
                    "middle_name" => "Ischo",
                    "last_name" => "Colombias",
                    "birthday" => "1988-08-10",
                    "address" => "130 Bedok North Street 2, Singapura 460130",
                    "phone_number" => "087789289120",
                    "nationality" => "singaporean",
                    "location" => "SG",
                    "date_joined" => "2012-08-02"
                ],
                "role" => "employee",
                "employment" => [
                    "grade" => "professional",
                    "position" => "advertising-manager",
                    "type" => "contractor"
                ],
                "manager" => 2,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "aylia@skilledin.io",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Aylia",
                    "middle_name" => null,
                    "last_name" => "Starla",
                    "birthday" => "1989-02-10",
                    "address" => "130 Bedok North Street 2, Singapura 460130",
                    "phone_number" => "062123445",
                    "nationality" => "singaporean",
                    "location" => "SG",
                    "date_joined" => "2012-08-02"
                ],
                "role" => "employee",
                "employment" => [
                    "grade" => "professional",
                    "position" => "business-analyst",
                    "type" => "internship"
                ],
                "manager" => 2,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "rendy@skilledin.io",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Rendy",
                    "middle_name" => "Bootstrap",
                    "last_name" => null,
                    "birthday" => "1988-08-10",
                    "address" => "8 Marina View, Singapura 018960",
                    "phone_number" => "0821349234345",
                    "nationality" => "singaporean",
                    "location" => "SG",
                    "date_joined" => "2010-08-02"
                ],
                "role" => "employee",
                "employment" => [
                    "grade" => "professional",
                    "position" => "advertising-sales-agent",
                    "type" => "fulltime"
                ],
                "manager" => 2,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "ruben.tampubolon@lumoshive.com",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Ruben",
                    "middle_name" => null,
                    "last_name" => null,
                    "birthday" => "",
                    "address" => "",
                    "phone_number" => "",
                    "nationality" => "indonesian",
                    "location" => "ID",
                    "date_joined" => ""
                ],
                "role" => "employee",
                "employment" => [
                    "grade" => "professional",
                    "position" => "software-engineer",
                    "type" => "fulltime"
                ],
                "manager" => 2,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "iman.maliki@lumoshive.com",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Iman",
                    "middle_name" => null,
                    "last_name" => "Maliki",
                    "birthday" => "",
                    "address" => "",
                    "phone_number" => "08283160993225",
                    "nationality" => "indonesian",
                    "location" => "ID",
                    "date_joined" => ""
                ],
                "role" => "employee",
                "employment" => [
                    "grade" => "professional",
                    "position" => "software-engineer",
                    "type" => "fulltime"
                ],
                "manager" => 2,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "fariz.rizky@lumoshive.com",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Faris",
                    "middle_name" => null,
                    "last_name" => "Rizky",
                    "birthday" => "",
                    "address" => "",
                    "phone_number" => "087780223354",
                    "nationality" => "indonesian",
                    "location" => "ID",
                    "date_joined" => ""
                ],
                "role" => "employee",
                "employment" => [
                    "grade" => "professional",
                    "position" => "software-engineer",
                    "type" => "fulltime"
                ],
                "manager" => 2,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "syahroni.hermawan@lumoshive.com",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Syahroni",
                    "middle_name" => null,
                    "last_name" => "Hermawan",
                    "birthday" => "",
                    "address" => "",
                    "phone_number" => "082217059470",
                    "nationality" => "indonesian",
                    "location" => "ID",
                    "date_joined" => ""
                ],
                "role" => "employee",
                "employment" => [
                    "grade" => "professional",
                    "position" => "software-engineer",
                    "type" => "fulltime"
                ],
                "manager" => 2,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],

            [
                "auth" =>[
                    "email" => "mauludy.rakhman@lumoshive.com",
                    "password" => Hash::make("password"),
                ],
                "profile" => [
                    "first_name" => "Mauludy",
                    "middle_name" => null,
                    "last_name" => "Rakhman",
                    "birthday" => null,
                    "address" => null,
                    "phone_number" => "081373896919",
                    "nationality" => "indonesian",
                    "location" => "ID",
                    "date_joined" => null
                ],
                "role" => "employee",
                "employment" => [
                    "grade" => "professional",
                    "position" => "software-engineer",
                    "type" => "fulltime"
                ],
                "manager" => 2,
                "dotline_manager" => 1,
                "system_status" => "activated"
            ],
        ];

    }

    private function combineToFullname($firstName, $middleName = '', $lastName = '')
    {
        if(empty($firstName)) return "No Name";

        $firstName = Str::ucfirst(Str::lower($firstName));

        $middleName = (!empty($middleName) && $middleName != null) ? ' '. Str::ucfirst(Str::lower($middleName)) : '';

        $lastName = (!empty($lastName) && $lastName != null) ? ' '. Str::ucfirst(Str::lower($lastName)) : '';

        return trim($firstName.''. $middleName.''.$lastName);
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = $this->getUsers();

        foreach ($data as $key => $value) {

            $permissions = Permission::get();

            // Find User
            $user = User::where('email', $value["auth"]["email"])->first();

            if(!$user) {
                // Create new user

                $fullname = $this->combineToFullname(
                    $value["profile"]['first_name'],
                    $value["profile"]['middle_name'],
                    $value["profile"]['last_name']
                );

                $user = User::create([
                    "name" => $fullname,
                    "email" => $value["auth"]["email"],
                    "password" => $value["auth"]["password"]
                ]);
            }


            // Find Role
            $role = Role::where('name', $value["role"])->first();

            if($role) {
                // Assign Permission To Role
                $user->assignRole([$role->id]);

                if($role->slug == 'hr-admin') {
                    $user->syncPermissions($permissions);
                }
            }

            // Create Profile
            if(!$user->profile) {
                $country = Nationality::where("nationality_code", Str::slug(Str::lower($value["profile"]["nationality"])) )->first();
                $location = Location::where("location_code", Str::upper($value["profile"]["location"]))->first();

                Profile::create([
                    "user_id" => $user->id,
                    "first_name" => $value["profile"]['first_name'],
                    "middle_name" => $value["profile"]['middle_name'],
                    "last_name" => $value["profile"]['last_name'],
                    "birthday" => Carbon::parse($value["profile"]['birthday'])->format("Y-m-d"),
                    "address" => $value["profile"]['address'],
                    "phone_number" =>  $value["profile"]['phone_number'],
                    "date_joined" => Carbon::parse($value["profile"]['date_joined'])->format("Y-m-d"),
                    "nationality_id" => $country ? $country->id : null,
                    "location_id" => $location ? $location->id : null,
                ]);
            }

            //"system_status" => "activated"
            $systemStatusSlug = Str::slug(Str::lower(trim($value["system_status"])));
            $systemStatus = SystemStatus::where("slug", $systemStatusSlug)->first();

            if($systemStatus) {
                $user->status_id = $systemStatus->id;
                $user->save();
            }

            if(!$user->employment) {
                $empType = EmploymentType::where('emp_type_slug', Str::lower($value["employment"]["type"]))->first();
                $position = JobPosition::where('job_slug', Str::lower($value["employment"]["position"]))->first();
                $grade = Grade::where('grade_slug', Str::lower($value['employment']['grade']))->first();

                $user->employment()->create([
                    "grade_id" => $grade->id,
                    "job_position_id" => $position->id,
                    "employment_type_id" => $empType->id,
                    "salary" => 0
                ]);

            }

            DirectReport::create([
                "user_id" => $user->id,
                "manager_id" => $value["manager"],
                "dotline_manager_id" => $value["dotline_manager"]
            ]);

        }

    }
}
