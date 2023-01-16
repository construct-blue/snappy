<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Psr\Http\Message\ServerRequestInterface;

interface RequestMatcher
{
    public function matches(ServerRequestInterface $request): bool;
}
