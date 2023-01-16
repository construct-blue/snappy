<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

enum Header: string implements RequestExtractorInterface
{
    case HOST = 'Host';
    case REFERER = 'Referer';
    case USER_AGENT = 'User-Agent';
    case ACCEPT_LANGUAGE = 'Accept-Language';
    case CACHE_CONTROL = 'Cache-Control';

    public function setTo(ResponseInterface $response, string $value): ResponseInterface
    {
        return $response->withHeader($this->value, $value);
    }

    public function getFrom(ServerRequestInterface $request, string $default = ''): string
    {
        $line = $request->getHeaderLine($this->value);
        if ($line === '') {
            return $default;
        }
        return $line;
    }

    public function matches(ResponseInterface $response): bool
    {
        return $response->hasHeader($this->value);
    }

    public function __invoke(ServerRequestInterface $request): string
    {
        return $this->getFrom($request);
    }
}
