<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Courses
        $courses = [
            'Science (10+2)',
            'Management (10+2)',
            'Humanities (10+2)',
            'Law (10+2)',
            'BBS',
            'BCA'
        ];
        foreach ($courses as $course) {
            \App\Models\Course::firstOrCreate(
                ['slug' => Str::slug($course)],
                [
                    'name' => $course,
                    'duration' => '2 Years',
                    'semester' => 'Annual',
                    'requirement' => 'SEE / Equivalent',
                    'starting_time' => '10:00 AM',
                    'closing_time' => '4:00 PM',
                    'image' => 'default.jpg',
                    'gallery' => '[]',
                    'status' => 1,
                    'description' => 'This is a detailed description for ' . $course . '. We offer state-of-the-art facilities.',
                    'fulldescription' => 'This is the full long description for ' . $course . ' detailing everything about the course.',
                ]
            );
        }

        // 2. Events
        $events = [
            'Annual Sports Week 2026',
            'Science Exhibition',
            'Cultural Fest',
            'Parent-Teacher Meeting'
        ];
        foreach ($events as $event) {
            \App\Models\Event::firstOrCreate(
                ['slug' => Str::slug($event)],
                [
                    'name' => $event,
                    'description' => 'Join us for the ' . $event . '. It will be a memorable experience for everyone involved.',
                    'venue' => 'School Premises',
                    'visit_date' => now()->addDays(rand(5, 30)),
                    'event_type' => 'event',
                    'image' => 'default.jpg',
                    'status' => 1
                ]
            );
        }

        // 3. Notices
        $notices = [
            ['title' => 'Admission Open for 2026-2027', 'show_in' => 'p'], // Popup
            ['title' => 'Holiday on account of local festival next week.', 'show_in' => 'm'], // Marquee
            ['title' => 'First Terminal Examination Routine Published', 'show_in' => 'n'], // Normal
            ['title' => 'Parent Teacher Meeting Scheduled', 'show_in' => 'n']
        ];
        foreach ($notices as $notice) {
            \App\Models\Notice::firstOrCreate(
                ['title' => $notice['title']],
                [
                    'slug' => Str::slug($notice['title']),
                    'description' => 'Please find the details regarding ' . $notice['title'] . '.',
                    'show_in' => $notice['show_in'],
                    'image' => 'default.jpg',
                ]
            );
        }

        // 4. Testimonials
        if (class_exists(\App\Models\Testimonial::class)) {
            $testimonials = [
                ['name' => 'John Doe', 'role' => 'Alumni (2020 Batch)', 'description' => 'The teachers were incredibly supportive and helped me shape my career.'],
                ['name' => 'Jane Smith', 'role' => 'Parent', 'description' => 'I have seen massive improvement in my child\'s confidence and academics.'],
                ['name' => 'Sita Sharma', 'role' => 'Current Student', 'description' => 'The environment is very welcoming and I love the extracurricular activities.']
            ];
            foreach ($testimonials as $t) {
                \App\Models\Testimonial::firstOrCreate(
                    ['name' => $t['name']],
                    [
                        'role' => $t['role'],
                        'description' => $t['description'],
                        'image' => 'default.jpg'
                    ]
                );
            }
        }

        // 5. College Messages
        $messages = [
            ['name' => 'Mr. Principal Name', 'designation' => 'Principal', 'message' => 'Welcome to our esteemed institution. We strive for excellence.', 'order' => 1],
            ['name' => 'Mr. Chairman Name', 'designation' => 'Chairman', 'message' => 'Our vision is to provide quality education that is accessible to everyone.', 'order' => 2]
        ];
        foreach ($messages as $msg) {
            \App\Models\CollegeMessage::firstOrCreate(
                ['name' => $msg['name']],
                [
                    'designation' => $msg['designation'],
                    'message' => $msg['message'],
                    'image' => 'default.jpg',
                    'order' => $msg['order'],
                    'status' => 1
                ]
            );
        }

        // 6. Faculties (Teachers)
        if (class_exists(\App\Models\Teacher::class)) {
            $teachers = [
                ['name' => 'Mr. Ram Kumar', 'role' => 'Science Teacher'],
                ['name' => 'Mrs. Sita Devi', 'role' => 'Maths Teacher'],
                ['name' => 'Mr. Hari Prasad', 'role' => 'English Teacher']
            ];
            foreach ($teachers as $t) {
                \App\Models\Teacher::firstOrCreate(
                    ['name' => $t['name']],
                    [
                        'role' => $t['role'],
                        'image' => 'default.jpg',
                        'staff_type' => 'teaching'
                    ]
                );
            }
        }

        // 7. Counter
        \App\Models\Counter::firstOrCreate(
            ['id' => 1],
            [
                'total_students' => 1500,
                'total_teachers' => 45,
                'total_courses' => 6,
                'total_classrooms' => 30
            ]
        );

        // 8. Banners
        if (class_exists(\App\Models\Banner::class)) {
            \App\Models\Banner::firstOrCreate(
                ['title1' => 'Welcome to', 'title2' => 'Shiksha Sandesh'],
                [
                    'status' => 1,
                    'image' => 'default.jpg',
                    'sort_order' => 1
                ]
            );
        }
    }
}
