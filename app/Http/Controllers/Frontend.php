<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\CampusCalendarEntry;
use App\Models\CollegeMessage;
use App\Models\Counter;
use App\Models\AboutUsFaq;
use App\Models\Course;
use App\Models\Event;
use App\Models\HomeSection;
use App\Models\Notice;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\SiteSetting;
use Illuminate\Support\Carbon;
use Pratiksh\Nepalidate\Services\NepaliDate;
use Pratiksh\Nepalidate\Services\EnglishDate;
use App\Helpers\NepaliCalendarHelper;

class Frontend extends Controller
{
    public function home()
    {
        $homeSections = HomeSection::orderBy('sort_order')->get()->keyBy('key');
        $orderedSectionKeys = $homeSections
            ->filter(fn($section) => $section->is_visible)
            ->sortBy('sort_order')
            ->keys()
            ->values();

        return view('frontend.pages.index', [
            'homeSections' => $homeSections,
            'orderedSectionKeys' => $orderedSectionKeys,
        ]);
    }

    public function manifest()
    {
        $siteSettings = SiteSetting::current();
        
        $logoUrl = $siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png');
        $primaryColor = $siteSettings->primary_color ?? '#1a4d8c';
        
        $manifest = [
            "name" => $siteSettings->site_name ?? "Shiksha Sandesh English School",
            "short_name" => $siteSettings->site_short_name ?? "SSES App",
            "description" => $siteSettings->site_tagline ?? "Excellence in Education",
            "start_url" => "/",
            "display" => "standalone",
            "background_color" => "#ffffff",
            "theme_color" => $primaryColor,
            "orientation" => "portrait",
            "icons" => [
                [
                    "src" => $logoUrl,
                    "sizes" => "192x192 512x512",
                    "type" => "image/png",
                    "purpose" => "any maskable"
                ]
            ]
        ];
        
        return response()->json($manifest);
    }

    public function aboutUs()
    {
        $siteSettings = SiteSetting::current();

        // Auto-heal missing tables if migrations haven't been run on production
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('about_us_faqs') || !\Illuminate\Support\Facades\Schema::hasColumn('site_settings', 'about_hero_title')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                \Illuminate\Support\Facades\Cache::forget('site_settings.current');
                $siteSettings = SiteSetting::current();
            }
        } catch (\Throwable $e) {
            // Silently continue with fallbacks
        }

        $aboutData = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('about_us')) {
                $aboutData = \App\Models\AboutUs::first();
            }
        } catch (\Throwable $e) {
            $aboutData = null;
        }

        $faqs = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('about_us_faqs')) {
                $faqs = \App\Models\AboutUsFaq::where('status', 1)->orderBy('sort_order')->get();
            }
        } catch (\Throwable $e) {
            $faqs = collect();
        }

        $messages = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('college_messages')) {
                $messages = \App\Models\CollegeMessage::where('status', 1)->orderBy('order')->get();
            }
        } catch (\Throwable $e) {
            $messages = collect();
        }

        $counter = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('counters')) {
                $counter = \App\Models\Counter::first();
            }
        } catch (\Throwable $e) {
            $counter = null;
        }

        $teachers = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('teachers')) {
                $teachers = \App\Models\Teacher::orderBy('sort_order', 'asc')->take(8)->get();
            }
        } catch (\Throwable $e) {
            $teachers = collect();
        }

        $courses = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('courses')) {
                $courses = \App\Models\Course::where(function ($q) {
                    $q->where('status', 1)->orWhereNull('status');
                })->get();
            }
        } catch (\Throwable $e) {
            $courses = collect();
        }

        return view('frontend.pages.aboutus', compact('siteSettings', 'aboutData', 'faqs', 'messages', 'counter', 'teachers', 'courses'));
    }

    public function coursesIndex()
    {
        $courses = Course::where(function ($q) {
            $q->where('status', 1)->orWhereNull('status');
        })->get();

        if ($courses->isEmpty() && Course::count() === 0) {
            try {
                (new \Database\Seeders\SchoolLevelsSeeder())->run();
                $courses = Course::where(function ($q) {
                    $q->where('status', 1)->orWhereNull('status');
                })->get();
            } catch (\Throwable $e) {
                // Fallback gracefully
            }
        }

        return view('frontend.pages.courses', compact('courses'));
    }

    public function courseDetail($slug)
    {
        $course = Course::where('slug', $slug)->first();
        if (!$course) {
            abort(404);
        }
        return view('frontend.pages.course_detail', compact('course'));
    }

    public function eventDetail($slug)
    {
        $event = Event::where('slug', '=', $slug)->first();
        return view('frontend.pages.event_detail', compact('event'));
    }

    public function noticeDetail($id)
    {
        $notice = Notice::where('id', '=', $id)->first();
        return view('frontend.pages.noticeDetail', compact('notice'));
    }

    public function noticeIndex()
    {
        $notices = Notice::latest()->get();

        return view('frontend.pages.notices', compact('notices'));
    }

    public function calendar(Request $request)
    {
        $settings = SiteSetting::current();
        $format = isset($settings->calendar_format) ? $settings->calendar_format : 'ad';

        $calendarEntries = CampusCalendarEntry::where('status', 1)->orderBy('start_date')->get();
        $publicEvents = Event::where('status', 1)->orderBy('visit_date')->get();

        $eventsAsEntries = $publicEvents->map(function ($event) {
            return (object) [
                'id' => 'evt_' . $event->id,
                'title' => $event->name,
                'start_date' => $event->visit_date,
                'end_date' => $event->visit_date,
                'entry_type' => $event->event_type ?? 'event',
                'entry_type_label' => ucfirst($event->event_type ?? 'Event'),
                'is_event' => true,
                'event_id' => $event->id,
                'event_slug' => $event->slug
            ];
        });

        $entries = $calendarEntries->concat($eventsAsEntries)->sortBy('start_date')->values();

        $todayAD = Carbon::now();
        $isCurrentMonth = false;
        $todayDay = 0;

        if ($format === 'bs') {
            try {
                $today = NepaliDate::create($todayAD);
                $currentYear = (int) $request->get('year', $today->year);
                $currentMonth = (int) $request->get('month', $today->month);

                if ($currentYear == $today->year && $currentMonth == $today->month) {
                    $isCurrentMonth = true;
                    $todayDay = $today->day;
                }
            } catch (\Throwable $e) {
                // Fallback to AD bounds if it fails
                $currentYear = 2081;
                $currentMonth = 1;
            }

            $helper = new NepaliCalendarHelper();
            $daysInMonth = $helper->getDaysInMonth($currentYear, $currentMonth);

            // Get first day of month in AD to find DayOfWeek
            $firstDayBS = "{$currentYear}-{$currentMonth}-01";
            try {
                $firstDayAD = EnglishDate::fromBS($firstDayBS)->toCarbon();
                $startDayOfWeek = $firstDayAD->dayOfWeek; // 0 (Sun) to 6 (Sat)
            } catch (\Throwable $e) {
                $startDayOfWeek = 0;
            }

            $monthName = $helper->getBSMonthInNepali($currentMonth) . ' ' . $helper->formattedNepaliNumber((string) $currentYear);
            $monthNameEnglish = $helper->getBSMonthInEnglish($currentMonth) . ' ' . $currentYear;

            $daysMapping = [];
            $altDaysMapping = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $daysMapping[$i] = $helper->formattedNepaliNumber((string) $i);
                try {
                    $ad = EnglishDate::fromBS("{$currentYear}-{$currentMonth}-{$i}")->toCarbon();
                    $altDaysMapping[$i] = $ad->format('d M');
                } catch (\Throwable $e) {
                    $altDaysMapping[$i] = '';
                }
            }

            $monthlyEntries = $entries->filter(function ($entry) use ($currentYear, $currentMonth) {
                try {
                    $bs = NepaliDate::create(Carbon::parse($entry->start_date));
                    return $bs->year == $currentYear && $bs->month == $currentMonth;
                } catch (\Throwable $e) {
                    return false;
                }
            })->groupBy(function ($entry) {
                try {
                    return NepaliDate::create(Carbon::parse($entry->start_date))->day;
                } catch (\Throwable $e) {
                    return 0;
                }
            });

            $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;
            $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;

            $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
            $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;

        } else {
            $currentYear = (int) $request->get('year', $todayAD->year);
            $currentMonth = (int) $request->get('month', $todayAD->month);

            if ($currentYear == $todayAD->year && $currentMonth == $todayAD->month) {
                $isCurrentMonth = true;
                $todayDay = $todayAD->day;
            }

            $date = Carbon::createFromDate($currentYear, $currentMonth, 1);
            $daysInMonth = $date->daysInMonth;
            $startDayOfWeek = $date->dayOfWeek;

            $monthName = $date->format('F Y');
            $monthNameEnglish = $date->format('F Y');

            $daysMapping = [];
            $altDaysMapping = [];
            $helper = new NepaliCalendarHelper();
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $daysMapping[$i] = (string) $i;
                try {
                    $bs = NepaliDate::create(Carbon::create($currentYear, $currentMonth, $i));
                    $altDaysMapping[$i] = $bs->day . ' ' . substr($helper->getBSMonthInEnglish($bs->month), 0, 3);
                } catch (\Throwable $e) {
                    $altDaysMapping[$i] = '';
                }
            }

            $monthlyEntries = $entries->filter(function ($entry) use ($currentYear, $currentMonth) {
                try {
                    $d = Carbon::parse($entry->start_date);
                    return $d->year == $currentYear && $d->month == $currentMonth;
                } catch (\Throwable $e) {
                    return false;
                }
            })->groupBy(function ($entry) {
                try {
                    return Carbon::parse($entry->start_date)->day;
                } catch (\Throwable $e) {
                    return 0;
                }
            });

            $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;
            $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;

            $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
            $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        }

        $weekdays = $format === 'bs'
            ? ['आइत', 'सोम', 'मङ्गल', 'बुध', 'बिहि', 'शुक्र', 'शनि']
            : ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        return view('frontend.pages.calendar', compact(
            'format',
            'currentYear',
            'currentMonth',
            'daysInMonth',
            'startDayOfWeek',
            'monthName',
            'monthNameEnglish',
            'daysMapping',
            'altDaysMapping',
            'monthlyEntries',
            'nextYear',
            'nextMonth',
            'prevYear',
            'prevMonth',
            'weekdays',
            'entries',
            'isCurrentMonth',
            'todayDay'
        ));
    }

    public function eventsIndex()
    {
        $events = Event::where('status', 1)->latest()->get();

        return view('frontend.pages.events', compact('events'));
    }

    public function pageDetail($slug)
    {
        $page = Page::where('slug', $slug)->where('status', 1)->firstOrFail();
        return view('frontend.pages.custom_page', compact('page'));
    }

    public function gallery()
    {
        $albums = \App\Models\GalleryAlbum::where('status', 'active')->latest()->get();
        // For masonry/grid layout without albums, we can fetch all or paginate
        $gallery = \App\Models\Gallery::latest()->get();

        return view('frontend.pages.gallery', compact('albums', 'gallery'));
    }

    public function contact()
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $operator = rand(0, 1) ? '+' : '*';
        $answer = $operator === '+' ? ($num1 + $num2) : ($num1 * $num2);

        session(['captcha_answer' => $answer]);

        $captchaQuestion = "What is $num1 $operator $num2?";

        return view('frontend.pages.contact', compact('captchaQuestion'));
    }
}
