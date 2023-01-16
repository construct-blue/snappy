<?php

namespace Blue\Core\Http;

use Psr\Http\Message\RequestInterface;

enum Method implements RequestMatcher
{
    case POST;
    case GET;

    public function matches(RequestInterface $request): bool
    {
        return $this->name === $request->getMethod();
    }

    public function __invoke(RequestInterface $request): bool
    {
        return $this->matches($request);
    }
}
