<?php

namespace App\Helpers;

use Pratiksh\Nepalidate\Services\DateConverter;

class NepaliCalendarHelper extends DateConverter
{
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
