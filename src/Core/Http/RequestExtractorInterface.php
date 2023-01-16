<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Psr\Http\Message\ServerRequestInterface;

interface RequestExtractorInterface
{
    public function getFrom(ServerRequestInterface $request, string $default = ''): string;
}
