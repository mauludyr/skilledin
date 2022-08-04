<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            LocationSeeder::class,
            NationalitySeeder::class,
            PositionSeeder::class,
            EmploymentTypeSeeder::class,
            TaskStatusSeeder::class,
            GradeSeeder::class,
            SystemStatusSeeder::class,
            PeriodSeeder::class,
            DurationSeeder::class,
            ObjectiveLevelSeeder::class,
            CustomTypeSeeder::class,
            MeasureSeeder::class,
            UserSeeder::class,
            SampleObjectiveSeeder::class,
            ProfileSettingSeeder::class,
            VisibilitySeeder::class,
            ReviewerGroupSeeder::class,
            CustomFieldSeeder::class,
            FieldSeeder::class,
            StatusTaskSeeder::class,
            StatusLabelSeeder::class,
            SettingFieldSeeder::class,
            OkrPotentialSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
