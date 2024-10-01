<?php

namespace FalconBaseServices\Helper;

class Helper
{

    public static function makeLink(string $str): string
    {
        // look for url in string
        $pattern_1 = '/((^(https?):{1}(\/){2}(www\.)?)|(^(www\.){1})|^(\w+\.\w+))([\w\?=#-:(\.|\/|$)]*)/im';
        $replacement_1 = '<a href="${1}${9}" target="_blank">${1}${9}</a>';
        $pass_1 = preg_replace($pattern_1, $replacement_1, $str);

        // add a leading http:// if not present
        $pattern_2 = '/href="(\w+\.\w+)([\w\?=#-_(\.|\/|$)]*)"/im';
        $replacement_2 = 'href="http://${1}${2}"';
        $pass_2 = rtrim(preg_replace($pattern_2, $replacement_2, $pass_1), "/");

        return $pass_2;
    }

    public static function checkEmail(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }

    public static function randomString(int $length = 10): string
    {
        return bin2hex(random_bytes($length));
    }

    public static function convertNum(string $num, $origin = 'en', $destination = 'fa'): string
    {
        $en = range(0, 9);
        $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $ar = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        return str_replace(${$origin}, ${$destination}, $num);
    }

    public static function checkMobile(string $mobile, $destination = 'ir'): bool
    {
        if ($destination == 'ir') {
            if (preg_match("/^([+]?(98))?0?9{1}[0-9]{9}$/", self::convertNum($mobile, 'fa', 'en'))) {
                return true;
            }
        }

        return false;
    }
}
