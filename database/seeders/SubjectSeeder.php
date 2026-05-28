<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\GradeLevel;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grade11 = GradeLevel::where('order', 11)->first();
        $grade12 = GradeLevel::where('order', 12)->first();

        $grade11Id = $grade11 ? $grade11->id : null;
        $grade12Id = $grade12 ? $grade12->id : null;

        $subjects = [
            // Elementary
            ['name' => 'Filipino', 'code' => 'FIL-ELEM', 'type' => 'elementary', 'grade_level_id' => null, 'track' => null],
            ['name' => 'English', 'code' => 'ENG-ELEM', 'type' => 'elementary', 'grade_level_id' => null, 'track' => null],
            ['name' => 'Mathematics', 'code' => 'MATH-ELEM', 'type' => 'elementary', 'grade_level_id' => null, 'track' => null],
            ['name' => 'Science', 'code' => 'SCI-ELEM', 'type' => 'elementary', 'grade_level_id' => null, 'track' => null],
            ['name' => 'Araling Panlipunan', 'code' => 'AP-ELEM', 'type' => 'elementary', 'grade_level_id' => null, 'track' => null],
            ['name' => 'MAPEH', 'code' => 'MAPEH-ELEM', 'type' => 'elementary', 'grade_level_id' => null, 'track' => null],
            ['name' => 'EPP/TLE', 'code' => 'EPP-ELEM', 'type' => 'elementary', 'grade_level_id' => null, 'track' => null],
            ['name' => 'ESP', 'code' => 'ESP-ELEM', 'type' => 'elementary', 'grade_level_id' => null, 'track' => null],
            
            // Junior High
            ['name' => 'Filipino', 'code' => 'FIL-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            ['name' => 'English', 'code' => 'ENG-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            ['name' => 'Mathematics', 'code' => 'MATH-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            ['name' => 'Science', 'code' => 'SCI-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            ['name' => 'Araling Panlipunan', 'code' => 'AP-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            ['name' => 'MAPEH', 'code' => 'MAPEH-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            ['name' => 'TLE', 'code' => 'TLE-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            ['name' => 'ESP', 'code' => 'ESP-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            ['name' => 'Computer Science', 'code' => 'CS-JHS', 'type' => 'junior_high', 'grade_level_id' => null, 'track' => null],
            
            // Senior High Grade 11 (Fixed Core)
            ['name' => 'Effective Communication', 'code' => 'EC-CORE', 'type' => 'senior_high', 'grade_level_id' => $grade11Id, 'track' => null],
            ['name' => 'Mabisang Komunikasyon', 'code' => 'MK-CORE', 'type' => 'senior_high', 'grade_level_id' => $grade11Id, 'track' => null],
            ['name' => 'Mathematics in the Modern World', 'code' => 'MMW-CORE', 'type' => 'senior_high', 'grade_level_id' => $grade11Id, 'track' => null],
            ['name' => 'Understanding the Self', 'code' => 'UTS-CORE', 'type' => 'senior_high', 'grade_level_id' => $grade11Id, 'track' => null],
            ['name' => 'Science, Technology, and Society', 'code' => 'STS-CORE', 'type' => 'senior_high', 'grade_level_id' => $grade11Id, 'track' => null],
            ['name' => 'Philippine History and Culture', 'code' => 'PHC-CORE', 'type' => 'senior_high', 'grade_level_id' => $grade11Id, 'track' => null],
            ['name' => 'Physical Education and Health 1', 'code' => 'PEH1-CORE', 'type' => 'senior_high', 'grade_level_id' => $grade11Id, 'track' => null],
            ['name' => 'Physical Education and Health 2', 'code' => 'PEH2-CORE', 'type' => 'senior_high', 'grade_level_id' => $grade11Id, 'track' => null],

            // Senior High Grade 12 STEM
            ['name' => 'Pre-Calculus', 'code' => 'PRECAL-STEM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'stem'],
            ['name' => 'Basic Calculus', 'code' => 'BASCAL-STEM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'stem'],
            ['name' => 'General Physics 1', 'code' => 'PHYS1-STEM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'stem'],
            ['name' => 'General Physics 2', 'code' => 'PHYS2-STEM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'stem'],
            ['name' => 'General Chemistry 1', 'code' => 'CHEM1-STEM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'stem'],
            ['name' => 'General Chemistry 2', 'code' => 'CHEM2-STEM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'stem'],

            // Senior High Grade 12 ABM
            ['name' => 'Business Math', 'code' => 'BMATH-ABM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'abm'],
            ['name' => 'Organization and Management', 'code' => 'ORGMGT-ABM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'abm'],
            ['name' => 'Fundamentals of ABM 1', 'code' => 'FABM1-ABM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'abm'],
            ['name' => 'Fundamentals of ABM 2', 'code' => 'FABM2-ABM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'abm'],
            ['name' => 'Applied Economics', 'code' => 'APPECO-ABM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'abm'],
            ['name' => 'Business Finance', 'code' => 'BUSFIN-ABM', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'abm'],

            // Senior High Grade 12 HUMSS
            ['name' => 'Creative Writing', 'code' => 'CW-HUMSS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'humss'],
            ['name' => 'Creative Nonfiction', 'code' => 'CNF-HUMSS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'humss'],
            ['name' => 'Introduction to World Religions', 'code' => 'IWR-HUMSS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'humss'],
            ['name' => 'Trends, Networks, and Critical Thinking', 'code' => 'TRENDS-HUMSS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'humss'],
            ['name' => 'Philippine Politics and Governance', 'code' => 'PPG-HUMSS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'humss'],
            ['name' => 'Disciplines and Ideas in the Social Sciences', 'code' => 'DISS-HUMSS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'humss'],

            // Senior High Grade 12 Sports
            ['name' => 'Human Anatomy', 'code' => 'HMA-SPORTS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'sports'],
            ['name' => 'Exercise and Sports Programming', 'code' => 'ESP-SPORTS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'sports'],
            ['name' => 'Sports Coaching and Officiating', 'code' => 'SCO-SPORTS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'sports'],
            ['name' => 'Safety and First Aid', 'code' => 'SFA-SPORTS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'sports'],
            ['name' => 'Sports Activity Management', 'code' => 'SAM-SPORTS', 'type' => 'senior_high', 'grade_level_id' => $grade12Id, 'track' => 'sports'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['code' => $subject['code']],
                $subject
            );
        }
    }
}
