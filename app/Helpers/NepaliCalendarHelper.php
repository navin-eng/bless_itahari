<?php

namespace App\Helpers;

use Pratiksh\Nepalidate\Services\DateConverter;

class NepaliCalendarHelper extends DateConverter
{
    public function __construct()
    {
        // The base package has incorrect days for 2083 BS. 
        // We override it here to fix Ashoj (month 6) having 31 days.
        $this->calendarData[83] = [2083, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30];
    }
    
    /**
     * Get the number of days in a specific Nepali month for a specific year.
     *
     * @param int $year  (e.g., 2081)
     * @param int $month (e.g., 1 for Baisakh)
     * @return int
     */
    public function getDaysInMonth(int $year, int $month): int
    {
        $yearIndex = $year - 2000;
        
        // Fallback to 30 days if out of bounds (package supports 2000-2089)
        if (!isset($this->calendarData[$yearIndex])) {
            return 30;
        }

        return $this->calendarData[$yearIndex][$month];
    }
}
