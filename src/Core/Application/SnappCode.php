<?php

declare(strict_types=1);

namespace Blue\Core\Application;

class SnappCode
{
    public static function build(?string $domain, ?string $path)
    {
        return trim(str_replace(['.', '/'], ['-', '-'], ($domain ?? '') . ($path ?? '')), '-');
    }
}
