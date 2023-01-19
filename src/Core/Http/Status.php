<?php

namespace Blue\Core\Http;

use Psr\Http\Message\ResponseInterface;

enum Status: int implements ResponseMatcher
{
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;

    case MOVED_PERMANENTLY = 301;
    case FOUND = 302;

    case BAD_REQUEST = 400;
    case NOT_FOUND = 404;
    case INTERNAL_SERVER_ERROR = 500;
    case GENERAL_ERROR = 512;
    case VALIDATION_ERROR = 513;

    public function matches(ResponseInterface $response): bool
    {
        return $this->value === $response->getStatusCode();
    }
}
