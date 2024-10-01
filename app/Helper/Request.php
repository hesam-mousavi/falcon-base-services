<?php

namespace FalconBaseServices\Helper;

class Request
{
    public static function headers(array|string $keys = null): array|string
    {
        $output = [];

        foreach (getallheaders() as $k => $v) {
            if (is_null($keys)) {
                $output[$k] = $v;
            } elseif (is_array($keys)) {
                foreach ($keys as $key) {
                    if ($key == $k) {
                        $output[$key] = $v;
                    }
                }
            } elseif ($keys == $k) {
                return $v;
            }
        }

        return $output;
    }

    public static function isAjax(): bool
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }
}
