<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Elementary
            ['name' => 'Filipino', 'code' => 'FIL-ELEM', 'type' => 'elementary'],
            ['name' => 'English', 'code' => 'ENG-ELEM', 'type' => 'elementary'],
            ['name' => 'Mathematics', 'code' => 'MATH-ELEM', 'type' => 'elementary'],
            ['name' => 'Science', 'code' => 'SCI-ELEM', 'type' => 'elementary'],
            ['name' => 'Araling Panlipunan', 'code' => 'AP-ELEM', 'type' => 'elementary'],
            ['name' => 'MAPEH', 'code' => 'MAPEH-ELEM', 'type' => 'elementary'],
            ['name' => 'EPP/TLE', 'code' => 'EPP-ELEM', 'type' => 'elementary'],
            ['name' => 'ESP', 'code' => 'ESP-ELEM', 'type' => 'elementary'],
            
            // Junior High
            ['name' => 'Filipino', 'code' => 'FIL-JHS', 'type' => 'junior_high'],
            ['name' => 'English', 'code' => 'ENG-JHS', 'type' => 'junior_high'],
            ['name' => 'Mathematics', 'code' => 'MATH-JHS', 'type' => 'junior_high'],
            ['name' => 'Science', 'code' => 'SCI-JHS', 'type' => 'junior_high'],
            ['name' => 'Araling Panlipunan', 'code' => 'AP-JHS', 'type' => 'junior_high'],
            ['name' => 'MAPEH', 'code' => 'MAPEH-JHS', 'type' => 'junior_high'],
            ['name' => 'TLE', 'code' => 'TLE-JHS', 'type' => 'junior_high'],
            ['name' => 'ESP', 'code' => 'ESP-JHS', 'type' => 'junior_high'],
            ['name' => 'Computer Science', 'code' => 'CS-JHS', 'type' => 'junior_high'],
            
            // Senior High
            ['name' => 'Oral Communication', 'code' => 'ORAL-SHS', 'type' => 'senior_high'],
            ['name' => 'Reading and Writing', 'code' => 'READ-SHS', 'type' => 'senior_high'],
            ['name' => '21st Century Literature', 'code' => 'LIT-SHS', 'type' => 'senior_high'],
            ['name' => 'General Mathematics', 'code' => 'GMATH-SHS', 'type' => 'senior_high'],
            ['name' => 'Earth Science', 'code' => 'ESCI-SHS', 'type' => 'senior_high'],
            ['name' => 'Personal Development', 'code' => 'PERDEV-SHS', 'type' => 'senior_high'],
            ['name' => 'Contemporary Arts', 'code' => 'ARTS-SHS', 'type' => 'senior_high'],
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::updateOrCreate(
                ['code' => $subject['code']],
                $subject
            );
        }
    }
}
