<?php

namespace Blue\Core\Http;

use Laminas\Diactoros\Response\RedirectResponse;
use Blue\Core\Application\Ingress\IngressResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PostRedirectGetMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if (Method::POST->matches($request) && Status::OK->matches($response)) {
            return new RedirectResponse(Header::REFERER->getFrom($request), 303);
        }
        return $response;
    }
}
