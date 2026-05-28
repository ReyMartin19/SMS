<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GradeLevel;


class GradeLevelSeeder extends Seeder
{
    public function run(): void
{
    $levels = [
        // Elementary
        ['name' => 'Grade 1', 'order' => 1, 'type' => 'elementary'],
        ['name' => 'Grade 2', 'order' => 2, 'type' => 'elementary'],
        ['name' => 'Grade 3', 'order' => 3, 'type' => 'elementary'],
        ['name' => 'Grade 4', 'order' => 4, 'type' => 'elementary'],
        ['name' => 'Grade 5', 'order' => 5, 'type' => 'elementary'],
        ['name' => 'Grade 6', 'order' => 6, 'type' => 'elementary'],

        // Junior High
        ['name' => 'Grade 7',  'order' => 7,  'type' => 'junior_high'],
        ['name' => 'Grade 8',  'order' => 8,  'type' => 'junior_high'],
        ['name' => 'Grade 9',  'order' => 9,  'type' => 'junior_high'],
        ['name' => 'Grade 10', 'order' => 10, 'type' => 'junior_high'],

        // Senior High
        ['name' => 'Grade 11', 'order' => 11, 'type' => 'senior_high'],
        ['name' => 'Grade 12', 'order' => 12, 'type' => 'senior_high'],
    ];

    foreach ($levels as $level) {
        GradeLevel::updateOrCreate(['name' => $level['name']], $level);
    }
}
}
