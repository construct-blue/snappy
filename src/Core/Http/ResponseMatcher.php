<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Psr\Http\Message\ResponseInterface;

interface ResponseMatcher
{
    public function matches(ResponseInterface $response): bool;
}
