<?php

declare(strict_types=1);

namespace Blue\Core\I18n;

use Locale;

enum Language: string
{
    public const DEFAULT = self::ENGLISH;

    case GERMAN = 'de';
    case ENGLISH = 'en';

    public function toLocale(Region $region = null): string
    {
        if ($region) {
            return Locale::composeLocale([
                Locale::LANG_TAG => $this->value,
                Locale::REGION_TAG => $region->value
            ]);
        }
        return Locale::composeLocale([
            Locale::LANG_TAG => $this->value
        ]);
    }

    public static function fromLocale(string $locale): self
    {
        return self::from(Locale::getPrimaryLanguage($locale));
    }

    public static function fromHttpHeader(string $header): self
    {
        return self::fromLocale(Locale::acceptFromHttp($header));
    }

    public function getName(Language $language = Language::DEFAULT): string
    {
        return Locale::getDisplayLanguage($this->toLocale(), $language->toLocale());
    }
}
