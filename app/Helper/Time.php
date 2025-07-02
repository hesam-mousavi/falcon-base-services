<?php

namespace FalconBaseServices\Helper;

use Carbon\Carbon;

class Time
{
    public static function translate($datetime, string $pattern = 'dd MMMM yyyy', string $locale = 'fa_IR', string $timezone = FALCON_BASE_TIME_ZONE): string
    {
        if (empty($datetime) || !$datetime instanceof \DateTimeInterface) {
            $datetime = self::now();
        }

        try {
            $formatter = new \IntlDateFormatter(
                $locale,
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                $timezone,
                \IntlDateFormatter::GREGORIAN,
                $pattern
            );

            return $formatter->format($datetime);
        } catch (\Exception $e) {
            logger('Cant Translate Time');
            return '';
        }
    }

    public static function now(\DateTimeZone|string|null $time_zone = FALCON_BASE_TIME_ZONE): \DateTimeInterface
    {
        return Carbon::now($time_zone);
    }
}
