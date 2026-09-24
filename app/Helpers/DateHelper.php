<?php

use App\Models\SiteSetting;
use Illuminate\Support\Carbon;
use Pratiksh\Nepalidate\Services\NepaliDate;

if (!function_exists('format_system_date')) {
    /**
     * Format a given date based on the system calendar setting (A.D. or B.S.)
     *
     * @param \Carbon\Carbon|string $date
     * @param string $adFormat The format to use if AD is selected (default 'd M Y')
     * @return string
     */
    function format_system_date($date, $adFormat = 'd M Y')
    {
        if (!$date) return '';

        try {
            $parsedDate = Carbon::parse($date);
        } catch (\Throwable $e) {
            return $date; // Not a valid date string
        }

        $settings = SiteSetting::current();
        
        if (isset($settings->calendar_format) && $settings->calendar_format === 'bs') {
            try {
                if ($adFormat === 'd') {
                    $details = toDetailBS($parsedDate);
                    return $details['day'] ?? $parsedDate->format('d');
                }
                if ($adFormat === 'M') {
                    $details = toDetailBS($parsedDate);
                    // Manually map month num to name since package lacks a direct 'M' format
                    $bsMonths = ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
                    return isset($details['month']) ? substr($bsMonths[(int)$details['month'] - 1], 0, 3) : $parsedDate->format('M');
                }

                $nepaliDateObj = NepaliDate::create($parsedDate);
                $formatted = $nepaliDateObj->toFormattedEnglishBSDate();
                // "4 Jestha 2082, Sunday" -> "4 Jestha 2082"
                $parts = explode(',', $formatted);
                return trim($parts[0]);
            } catch (\Throwable $e) {
                // Fallback to AD if conversion fails
                return $parsedDate->format($adFormat);
            }
        }
        
        // Default AD formatting
        return $parsedDate->format($adFormat);
    }
}

if (!function_exists('get_today_nepali_date')) {
    /**
     * Get today's Nepali date with day of week.
     * Example: "आइतबार, २९ बैशाख २०८१" or english translation "Sunday, 29 Baisakh 2081"
     * 
     * @return string
     */
    function get_today_nepali_date()
    {
        try {
            $nepaliDateObj = NepaliDate::create(Carbon::now());
            return $nepaliDateObj->toFormattedNepaliBSDate();
        } catch (\Throwable $e) {
            return Carbon::now()->format('l, d M Y');
        }
    }
}
