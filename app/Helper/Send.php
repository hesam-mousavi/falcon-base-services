<?php

namespace FalconBaseServices\Helper;

use FalconBaseServices\Services\Sender\Contracts\Email;
use FalconBaseServices\Services\Sender\Implements\SMS\Iran\KavehNegar;

class Send
{
    protected static array $sms_handlers = ['kaveh_negar'];

    public static function sms(string $mobile, string $message, $with = null): bool
    {
        if ($with == 'kaveh_negar') {
            return self::withKavehNegar($mobile, $message);
        } else {
            switch (self::$sms_handlers[rand(0, count(self::$sms_handlers) - 1)]) {
                case 'kaveh_negar':
                    return self::withKavehNegar($mobile, $message);
            }
        }
    }

    protected static function withKavehNegar(string|int $mobile, string $message): bool
    {
        return (new KavehNegar())->send($mobile, $message);
    }

    public static function email($to, $subject, $content, string $from = null, array $bcc = null): bool
    {
        return BASE_CONTAINER->get(Email::class)->send($to, $subject, $content, $from, $bcc);
    }
}
