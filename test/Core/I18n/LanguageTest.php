<?php

declare(strict_types=1);

namespace BlueTest\Core\I18n;

use Blue\Core\I18n\Language;
use Blue\Core\I18n\Region;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    public function testToLocale()
    {
        $this->assertEquals('de_AT', Language::GERMAN->toLocale(Region::AUSTRIA));
        $this->assertEquals('de', Language::GERMAN->toLocale());
    }

    public function testFromLocale()
    {
        $this->assertEquals(Language::GERMAN, Language::fromLocale('de_AT'));
    }

    public function testLocalizedName()
    {
        $this->assertEquals('German', Language::GERMAN->getName(Language::ENGLISH));
        $this->assertEquals('Deutsch', Language::GERMAN->getName(Language::GERMAN));
    }
}
