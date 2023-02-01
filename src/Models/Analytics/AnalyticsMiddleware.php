<?php

declare(strict_types=1);

namespace Blue\Models\Analytics;

use Blue\Core\Http\QueryParameter;
use Blue\Core\Http\RequestAttribute;
use Blue\Models\Analytics\Tracker\RequestTracker;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AnalyticsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = RequestAttribute::REQUEST_ID->setTo($request, uniqid());
        $request = RequestAttribute::REQUEST_TIMESTAMP->setTo($request, time());
        $tracker = new RequestTracker();
        $tracker->queueSave();
        $tracker->setRequest($request);
        if (QueryParameter::analytics_event->getFrom($request)) {
            return new Response();
        }
        $response = $handler->handle($request);
        $tracker->setResponse($response);
        return $response;
    }
}
