<?php

declare(strict_types=1);

namespace Blue\Core\Analytics;

use Laminas\Diactoros\Response;
use Blue\Core\Http\Attribute;
use Blue\Core\Http\QueryParameter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AnalyticsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = Attribute::REQUEST_ID->setTo($request, uniqid());
        $request = Attribute::REQUEST_TIMESTAMP->setTo($request, time());
        $tracker = new RequestTracker();
        $tracker->queueSave();
        $tracker->setRequest($request);
        if (QueryParameter::analytics_event->getFrom($request)) {
            return new Response();
        }
        $response = $handler->handle($request);
        $tracker->setResponse($response);
        $calc = new DayCalculation();
        $calc->queueExecution();
        return $response;
    }
}
