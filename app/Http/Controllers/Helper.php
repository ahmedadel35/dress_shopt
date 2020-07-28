<?php

namespace App\Http\Controllers;

class Helper
{
    public static
    /**
     * @copyright 2020 https://gist.github.com/RadGH/84edff0cc81e6326029c
     * 
     * @param $n
     * @return string
     * Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
     */
    function shortNum($n)
    {
        $n_format = 0;
        $suffix = '';

        if ($n > 0 && $n < 1000) {
            // 1 - 999
            $n_format = floor($n);
            $suffix = '';
        } else if ($n >= 1000 && $n < 1000000) {
            // 1k-999k
            $n_format = floor($n / 1000);
            $suffix = self::getAddon('k');
        } else if ($n >= 1000000 && $n < 1000000000) {
            // 1m-999m
            $n_format = floor($n / 1000000);
            $suffix = self::getAddon('m');
        } else if ($n >= 1000000000 && $n < 1000000000000) {
            // 1b-999b
            $n_format = floor($n / 1000000000);
            $suffix = self::getAddon('b');
        } else if ($n >= 1000000000000) {
            // 1t+
            $n_format = floor($n / 1000000000000);
            $suffix = self::getAddon('t');
        }

        return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
    }

    protected static function getAddon($addon): string
    {
        $locale = app()->getLocale();
        if ($locale !== 'ar') {
            return strtoupper($addon . '+');
        }

        if ($addon === 'k') {
            return ' +ألف ';
        } elseif ($addon === 'm') {
            return ' +مليون ';
        } elseif ($addon === 'b') {
            return ' +مليار ';
        } elseif ($addon === 't') {
            return ' +تريليون ';
        }
    }
}
