<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Psr\Http\Message\ServerRequestInterface;

enum QueryParameter implements RequestExtractorInterface
{
    case utm_source;
    case utm_medium;
    case utm_campaign;
    case utm_term;
    case utm_content;
    case analytics_rid;
    case analytics_rt;
    case analytics_event;
    case analytics_click;

    public function getFrom(ServerRequestInterface $request, string $default = ''): string
    {
        return $request->getQueryParams()[$this->name] ?? $default;
    }
}
