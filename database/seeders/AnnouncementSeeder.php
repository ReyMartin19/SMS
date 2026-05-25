<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::where('role', 'superadmin')->first();
        
        if (!$admin) {
            return;
        }

        // Announcement 1: Pinned, targeted to everyone
        Announcement::create([
            'user_id' => $admin->id,
            'title' => '🌟 Welcome to the New School Year 2026-2027!',
            'body' => "Dear Students, Teachers, and Parents,\n\nWe are absolutely thrilled to welcome you back for the academic year 2026-2027! We hope you had a restful break and are ready to embark on a new journey of learning, discovery, and growth.\n\nPlease check your respective portals for schedules, class assignments, and grade book links. Let's make this year extraordinary!",
            'audience' => 'all',
            'is_pinned' => true,
            'published_at' => now()->subDays(2),
            'expiry_date' => now()->addMonths(3),
        ]);

        // Announcement 2: Targeted to Teachers
        Announcement::create([
            'user_id' => $admin->id,
            'title' => '📝 Faculty Meeting: Grading Guidelines Review',
            'body' => "Attention All Teachers,\n\nThere will be a mandatory faculty meeting this coming Friday, May 29, 2026, at 2:00 PM in the Audio-Visual Room (AVR).\n\nAgenda:\n1. Review of DepEd grading policies.\n2. Standardizing quarterly assessment sheets.\n3. Using the new bulk grade entry system.\n\nPlease ensure all your class assignments are up to date before the meeting. Attendance is compulsory.",
            'audience' => 'teacher',
            'is_pinned' => false,
            'published_at' => now()->subDay(),
            'expiry_date' => now()->addDays(7),
        ]);

        // Announcement 3: Pinned, targeted to Parents
        Announcement::create([
            'user_id' => $admin->id,
            'title' => '👨‍👩‍👧 General PTA Assembly - Saturday',
            'body' => "Dear Parents & Guardians,\n\nYou are cordially invited to attend our First General Parent-Teacher Association (PTA) Assembly for this school year on Saturday, May 30, 2026, at 9:00 AM in the School Gymnasium.\n\nWe will be discussing school policies, safety measures, upcoming school activities, and electing the new set of PTA officers. Your presence and active participation are highly valued.",
            'audience' => 'parent',
            'is_pinned' => true,
            'published_at' => now()->subHours(6),
            'expiry_date' => now()->addDays(5),
        ]);

        // Announcement 4: Targeted to Students
        Announcement::create([
            'user_id' => $admin->id,
            'title' => '🏀 Tryouts: School Varsity Teams',
            'body' => "Hey Eagles!\n\nAre you ready to represent the school? Tryouts for our Varsity Basketball, Volleyball, and Football teams are officially open next week!\n\nSchedule:\n- Basketball: Monday & Wednesday, 4:00 PM\n- Volleyball: Tuesday & Thursday, 4:00 PM\n- Football: Friday, 3:30 PM\n\nLocation: School Gym & Field. Bring your own athletic gear and a copy of your current enrollment form signed by your guardian. Fly high!",
            'audience' => 'student',
            'is_pinned' => false,
            'published_at' => now()->subHours(2),
            'expiry_date' => now()->addDays(10),
        ]);

        // Announcement 5: Draft (future publication date)
        Announcement::create([
            'user_id' => $admin->id,
            'title' => '📅 [Draft] Upcoming Midterm Examination Schedule',
            'body' => "This is a pre-scheduled announcement for the Midterm Examinations. The exams will run from June 22 to June 26, 2026. Detailed schedules per subject and section will be uploaded shortly.",
            'audience' => 'all',
            'is_pinned' => false,
            'published_at' => now()->addWeeks(2),
            'expiry_date' => now()->addWeeks(3),
        ]);

        // Announcement 6: Expired Announcement
        Announcement::create([
            'user_id' => $admin->id,
            'title' => '⚠️ [Expired] System Maintenance Notice',
            'body' => "The student portal will undergo scheduled database optimizations on May 20, 2026, from 10:00 PM to 2:00 AM. Portals may be temporarily offline during this period.",
            'audience' => 'all',
            'is_pinned' => false,
            'published_at' => now()->subDays(10),
            'expiry_date' => now()->subDays(3),
        ]);
    }
}
