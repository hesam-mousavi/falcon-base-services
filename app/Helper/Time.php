<?php

namespace FalconBaseServices\Helper;

use Carbon\Carbon;
use IntlDateFormatter;

class Time
{
    public static function translate(string $dateTime = null, string $pattern = "dd MMMM yyyy", $locale = null): string
    {
        if (is_null($dateTime)) {
            $dateTime = self::now();
        }

        if (is_null($locale)) {
            $locale = get_locale();
        }

        $formatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'GMT'.FALCON_BASE_TIME_ZONE,
            IntlDateFormatter::TRADITIONAL,
            $pattern,
        );

        return $formatter->format(strtotime($dateTime));
    }

    public static function now(\DateTimeZone|string|int|null $time_zone = FALCON_BASE_TIME_ZONE): string
    {
        return Carbon::now($time_zone)->toDateTimeString();
    }
}
