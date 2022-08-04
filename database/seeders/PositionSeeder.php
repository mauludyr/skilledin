<?php

namespace Database\Seeders;

use App\Models\JobPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PositionSeeder extends Seeder
{

    private function getPositions()
    {
        return collect([
            "Chief Executive Officer (CEO)",
            "Chief Operating Officer (COO)",
            "Chief Financial Officer (CFO)",
            "Chief Marketing Officer (CMO)",
            "Chief Technology Officer (CTO)",
            "President",
            "Vice President",
            "Executive Assistant",
            "Marketing Manager",
            "Product Manager",
            "Project Manager",
            "Finance Manager",
            "Human Resources Manager",
            "Administrative Services Manager",
            "adult education teacher",
            "advertising manager",
            "advertising sales agent",
            "Marketing Specialist",
            "Business Analyst",
            "Human Resource Personnel",
            "Accountant",
            "Sales Representative",
            "Customer Sservice Representative",
            "Administrative Assistant",
            "IT Manager",
            "Web Designer",
            "Software Engineer",
            "Software Developer",
            "Backend Developer",
            "Frontend Developer",
            "Backend Engineer",
            "Frontend Engineer",
            "IT Analyst",
            "IT Support",
            "communications equipment operator",
            "communications teacher",
            "community association manager",
            "community service manager",
            "compensation and benefits manager",
            "Trainer",
            "data entry keyer",
            "database administrator",
            "information security analyst",
            "information systems manager",
            "interpreter",
            "legal assistant",
            "legal secretary",
            "loan officer",
            "lobby attendant",
            "locker room attendant",
            "product promoter",
            "public relations manager",
            "public relations specialist",
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getPositions() as $value) {
            $name = ucwords(trim($value));
            $slug = Str::slug(Str::lower($value));

            $findPosition = JobPosition::where('job_slug', $slug)->first();

            if(!$findPosition) {
                JobPosition::create([
                    "job_name" => $name,
                    "job_slug" => $slug,
                ]);
            }

        }
    }
}
