<?php

namespace FalconBaseServices\Helper;


use FalconBaseServices\Services\Sender\Implements\SMS\SMSFactory;

class Send
{
    public static function sms(string $mobile, string $message, $with = 'kaveh_negar'): bool
    {
        return (new SMSFactory())->make($with)->send($mobile, $message);
    }

    public static function email($to, $subject, $content, string $from = null, array $bcc = null): bool
    {
        return falconEmail()->send($to, $subject, $content, $from, $bcc);
    }
}
