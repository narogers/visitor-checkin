<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * Helpers to handle conversion of strings to dates, date comparisons, and
 * formatting for the presentation layer
 */
class DateHelper {
  /*
   * Given an abstract string like "today" or "last month" convert it to
   * a meaningful date label such as "December 2 to 8, 2016"
   *
   * @param range
   * @return string $label
   */
  public static function labelFor($date) {
    $date_label = "";
   
    switch ($date) {
      case 'today':
        $date_label = Carbon::now()->format('F jS, Y');
        break;
      case 'week':
        $date_label = Carbon::now()->startOfWeek()->format('F j') .
          " to " .
          Carbon::now()->format('F jS, Y');
        break;
      case 'month':
        $date_label = Carbon::now()->startOfMonth()->format('F j') .
          " to " .
          Carbon::now()->format('F jS, Y');
        break;
      case "lastmonth":
        $date_label = Carbon::now()->startOfMonth()->subMonth(1)->format("F j") .
          " to " .
          Carbon::now()->subMonth(1)->endOfMonth()->format('F jS, Y');
    }   
  
    return $date_label;
  }

  /**
   * Generate a date range relative to today based on "today", "week", 
   * "month", or "lastmonth" properties
   *
   * @param string $date
   * @return array $range
   */
  public static function rangeFor($date) {
    $range = [];
    
    switch ($date) {
      case "today":
        // Midnight to current time
        $range = [Carbon::today(), Carbon::now()];
        break;
      case "week":
        $range = [Carbon::today()->startOfWeek(), Carbon::now()];
        break;
      case "month":
        $range = [Carbon::today()->startOfMonth(), Carbon::now()];
        break;
      case "lastmonth":
        $range = [Carbon::today()->startOfMonth()->subMonth(1),
                  Carbon::now()->subMonth(1)->endOfMonth()];
    }

    return $range;
  }

  /**
   * Formats a date into a human readable structure
   *
   * @param $date
   * @param string $format (optional)
   * @return string
   */
  public static function format($date, $format = null) {
    $mask = isset($format) ? $format : "F jS";
    return Carbon::parse($date)->format($mask); 
  }
 
  /**
   * Returns the list of preset ranges
   *
   * @return array
   */
  public static function ranges() {
    return ['today', 'week', 'month', 'lastmonth'];
  }
}
