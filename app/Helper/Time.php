<?php

namespace FalconBaseServices\Helper;

use Carbon\Carbon;
use DateTimeInterface;
use DateTimeZone;


class Time
{
    public static function translate(
        DateTimeInterface|string|int|null $datetime = null,
        string $pattern = 'dd MMMM yyyy',
        string $locale = null,
        DateTimeZone|string|null $timezone = null,
        int $calendar = \IntlDateFormatter::TRADITIONAL
    ): string {
        try {
            $timezone = self::normalizeTimezone($timezone);

            if (empty($datetime)) {
                $datetime = self::now($timezone);
            }

            if (is_int($datetime)) {
                $datetime = (new \DateTimeImmutable('@' . $datetime))->setTimezone($timezone);
            }

            if (is_string($datetime)) {
                $datetime = new \DateTime($datetime, $timezone);
            }

            if (!$datetime instanceof DateTimeInterface) {
                $datetime = self::now($timezone);
            }

            $formatter = new \IntlDateFormatter(
                $locale ?? get_locale(),
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                $timezone,
                $calendar,
                $pattern
            );

            return $formatter->format($datetime);
        } catch (\Exception $e) {
            logger('Can’t Translate Time: ' . $e->getMessage());
            return '';
        }
    }


    public static function now(DateTimeZone|string|null $timezone = null): DateTimeInterface
    {
        $timezone = self::normalizeTimezone($timezone);
        return Carbon::now($timezone);
    }

    protected static function normalizeTimezone(DateTimeZone|string|null $timezone): DateTimeZone
    {
        if (is_null($timezone)) {
            return wp_timezone();
        }

        if ($timezone instanceof DateTimeZone) {
            return $timezone;
        }

        try {
            return new DateTimeZone($timezone);
        } catch (\Exception $e) {
            logger('Invalid timezone provided: ' . $timezone);
            return wp_timezone();
        }
    }
}
