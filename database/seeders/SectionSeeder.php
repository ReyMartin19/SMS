<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GradeLevel;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            'Sampaguita',
            'Rosal',
            'Ilang-Ilang',
        ];
    
        $gradeLevels = GradeLevel::all();
    
        foreach ($gradeLevels as $level) {
            foreach ($sections as $section) {
                Section::create([
                    'grade_level_id' => $level->id,
                    'name'           => $section,
                    'room_number'    => null,
                    'capacity'       => 40,
                ]);
            }
        }
    }
}
