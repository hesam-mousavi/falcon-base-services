<?php

namespace FalconBaseServices\Helper;

use Carbon\Carbon;
use IntlDateFormatter;

class Time
{
    public static function translate(string $dateTime, string $pattern = "dd MMMM yyyy", $locale = 'fa_IR'): string
    {
        if (empty($dateTime)) {
            $dateTime = self::now();
        }

        $formatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            FALCON_BASE_TIME_ZONE,
            IntlDateFormatter::TRADITIONAL,
            $pattern,
        );

        return $formatter->format(\strtotime($dateTime));
    }

    public static function now(\DateTimeZone|string|int|null $time_zone = FALCON_BASE_TIME_ZONE): string
    {
        return Carbon::now($time_zone)->toDateTimeString();
    }
}
