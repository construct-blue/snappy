<?php

declare(strict_types=1);

namespace Blue\Core\I18n;

use Locale;

enum Region: string
{
    public const DEFAULT = self::WORLD;

    case AUSTRIA = 'AT';
    case GERMANY = 'DE';
    case SWITZERLAND = 'CH';
    case WORLD = '001';

    public function toLocale(Language $language): string
    {
        return Locale::composeLocale([
            Locale::REGION_TAG => $this->value,
            Locale::LANG_TAG => $language->value
        ]);
    }

    public static function fromLocale(string $locale): self
    {
        return self::from(Locale::getRegion($locale));
    }

    public static function fromHttpHeader(string $header): self
    {
        return self::fromLocale(Locale::acceptFromHttp($header));
    }

    public function getName(Language $language = Language::DEFAULT): string
    {
        return Locale::getDisplayRegion($this->toLocale(Language::ENGLISH), $language->toLocale($this));
    }

    public function getFlag(): string
    {
        if ($this->value == '001') {
            return mb_convert_encoding('&#127758;', 'UTF-8', 'HTML-ENTITIES');
        }
        return mb_convert_encoding('&#' . (127397 + ord($this->value[0])) . ';', 'UTF-8', 'HTML-ENTITIES')
            . mb_convert_encoding('&#' . (127397 + ord($this->value[1])) . ';', 'UTF-8', 'HTML-ENTITIES');
    }
}
