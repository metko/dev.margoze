<?php

use Carbon\Carbon;

if (!function_exists('human_date')) {
    /**
     * Returns a human date readble.
     *
     * @param int $bytes
     *                      Bytes contains the size of the bytes to convert
     * @param int $decimals
     *                      Number of decimal places to be returned
     *
     * @return string a string in human readable format
     *
     * */
    function human_date($date)
    {
        $date = Carbon::parse($date)
               ->locale('fr_FR')->isoFormat('D MMMM YYYY');

        return $date;
    }
}

if (!function_exists('days_count')) {
    /**
     * Returns a human date readble.
     *
     * @param int $bytes
     *                      Bytes contains the size of the bytes to convert
     * @param int $decimals
     *                      Number of decimal places to be returned
     *
     * @return string a string in human readable format
     *
     * */
    function days_count($date)
    {
        $diff = Carbon::parse($date);
        $date = Carbon::now()->diffIndays($diff);

        return $date;
    }
}
