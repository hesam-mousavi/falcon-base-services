<?php

namespace FalconBaseServices\Enum;

enum HTTPStatus: int
{
    case OK = 200;
    case CREATED = 201;
    case MOVED_PERMANENTLY = 301;
    case NOT_MODIFIED = 304;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case UNPROCESSABLE_CONTENT = 422;
    case TOO_MANY_REQUESTS = 429;
    case INTERNAL_SERVER_ERROR = 500;
    case SERVICE_UNAVAILABLE = 503;

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $case): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $case->name)));
    }
}
