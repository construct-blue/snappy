<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Psr\Http\Message\ServerRequestInterface;

enum Attribute: string
{
    case APPS = 'attr_apps';
    case REQUEST_ID = 'attr_request_id';
    case REQUEST_TIMESTAMP = 'attr_request_timestamp';

    public function getFrom(ServerRequestInterface $request, $default = null)
    {
        return $request->getAttribute($this->value, $default);
    }

    public function setTo(ServerRequestInterface $request, $value): ServerRequestInterface
    {
        return $request->withAttribute($this->value, $value);
    }
}
