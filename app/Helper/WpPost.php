<?php

namespace FalconBaseServices\Helper;

use FalconBaseServices\Enum\HTTPStatus;

class WpPost
{
    public static function send(string $url, array $postFields, string $method = 'POST', array $headers = []): array
    {
        $headers = array_merge($headers, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response = wp_remote_post($url, [
            'headers' => $headers,
            'body' => json_encode($postFields),
            'method' => $method,
        ]);

        return $response;
    }

    public static function isSuccess($response): bool
    {
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == HTTPStatus::OK->value)
            return true;

        return false;
    }

}
