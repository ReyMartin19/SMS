<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'school_name' => 'School Management System',
            'school_address' => 'Address, City, Province',
            'school_phone' => '',
            'school_email' => '',
            'school_logo' => null,
            'principal_name' => '',
            'school_division' => '',
            'school_district' => '',
            'school_id' => '', // DepEd School ID
            'grading_passing_grade' => '75',
            'report_card_footer' => 'This is an official document of the school.',
        ];

        foreach ($defaults as $key => $value) {
            SystemSetting::set($key, $value);
        }
    }
}
