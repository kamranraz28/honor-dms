<?php

namespace App\Helpers;

use DateTime;

class DateHelper
{
    /**
     * Parse CSV date into Y-m-d H:i:s
     * Supports multiple formats
     */
    public static function parseCsvDate($dateStr)
    {
        if (!$dateStr) {
            return null;
        }

        $dateStr = trim($dateStr);

        // Format: 1/31/2024 10:30:00 AM
        $dt = DateTime::createFromFormat('n/j/Y h:i:s A', $dateStr);
        if ($dt !== false) {
            return $dt->format('Y-m-d H:i:s');
        }

        // Fallback to strtotime
        $timestamp = strtotime($dateStr);
        if ($timestamp !== false) {
            return date('Y-m-d H:i:s', $timestamp);
        }

        return null;
    }
}
