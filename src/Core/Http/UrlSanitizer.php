<?php

declare(strict_types=1);

namespace Blue\Core\Http;

final class UrlSanitizer
{
    public static function hostWithPath(string $url): string
    {
        return parse_url($url, PHP_URL_HOST) . self::path($url);
    }

    public static function path(string $url): string
    {
        return parse_url($url, PHP_URL_PATH);
    }
}
