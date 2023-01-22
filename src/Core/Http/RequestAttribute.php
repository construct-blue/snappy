<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Blue\Core\Application\Session\Session;
use Blue\Core\Application\Snapp\SnappRouteResult;
use Psr\Http\Message\ServerRequestInterface;

enum RequestAttribute: string
{
    case SNAPP_ROUTES = 'attr_snapp_routes';
    case SNAPP_ROUTE_RESULT = SnappRouteResult::class;
    case SESSION = Session::class;
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
