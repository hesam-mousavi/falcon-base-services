<?php

namespace FalconBaseServices\Helper;

use FalconBaseServices\Enum\HTTPStatus;

class Response
{
    public static function ok(): void
    {
        self::json(status: HTTPStatus::OK->value);
    }

    public static function json($message = '', $data = null, $status = 200)
    {
        $output['message'] = $message;
        if (!is_null($data)) {
            $output['data'] = $data;
        }

        wp_send_json($output, $status);
        die();
    }

    public static function created(): void
    {
        self::json(status: HTTPStatus::CREATED->value);
    }

    public static function MovedPermanently(): void
    {
        self::json(status: HTTPStatus::MOVED_PERMANENTLY->value);
    }

    public static function notModified(): void
    {
        self::json(status: HTTPStatus::NOT_MODIFIED->value);
    }

    public static function badRequest(): void
    {
        self::json(status: HTTPStatus::BAD_REQUEST->value);
    }

    public static function unauthorized(): void
    {
        self::json(status: HTTPStatus::UNAUTHORIZED->value);
    }

    public static function forbidden(): void
    {
        self::json(status: HTTPStatus::FORBIDDEN->value);
    }

    public static function notFound(): void
    {
        self::json(status: HTTPStatus::NOT_FOUND->value);
    }

    public static function methodNotAllowed(): void
    {
        self::json(status: HTTPStatus::METHOD_NOT_ALLOWED->value);
    }

    public static function unprocessableContent(): void
    {
        self::json(status: HTTPStatus::UNPROCESSABLE_CONTENT->value);
    }

    public static function tooManyRequests(): void
    {
        self::json(status: HTTPStatus::TOO_MANY_REQUESTS->value);
    }

    public static function internalServerError(): void
    {
        self::json(status: HTTPStatus::INTERNAL_SERVER_ERROR->value);
    }

    public static function serviceUnavailable(): void
    {
        self::json(status: HTTPStatus::SERVICE_UNAVAILABLE->value);
    }

}
