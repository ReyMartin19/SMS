<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GradeLevelSeeder::class,
            SubjectSeeder::class,
            SchoolYearSeeder::class,
            SectionSeeder::class,
            AnnouncementSeeder::class,
            SystemSettingSeeder::class,
        ]);
    }
}
