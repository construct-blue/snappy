<?php

declare(strict_types=1);

namespace Blue\Core\I18n;

use Stringable;

function __(null|string|Stringable $message, ?string $locale = null)
{
    if (null === $message) {
        return null;
    }
    if (null === $locale) {
        $locale = Language::DEFAULT->toLocale();
    }
    return Translator::instance($locale)->translate((string)$message);
}

function __pl(null|string|Stringable $message, int $count, ?string $locale)
{
    if (null === $message) {
        return null;
    }
    if (null === $locale) {
        $locale = Language::DEFAULT->toLocale();
    }
    return Translator::instance($locale)->translatepl((string)$message, $count);
}
