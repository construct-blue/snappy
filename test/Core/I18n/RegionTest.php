<?php

declare(strict_types=1);

namespace BlueTest\Core\I18n;

use Blue\Core\I18n\Language;
use Blue\Core\I18n\Region;
use PHPUnit\Framework\TestCase;

class RegionTest extends TestCase
{
    public function testToLocale()
    {
        $this->assertEquals('de_AT', Region::AUSTRIA->toLocale(Language::GERMAN));
    }

    public function testFromLocale()
    {
        $this->assertEquals(Region::AUSTRIA, Region::fromLocale('de_AT'));
    }

    public function testLocalizedName()
    {
        $this->assertEquals('Austria', Region::AUSTRIA->getName(Language::ENGLISH));
        $this->assertEquals('Österreich', Region::AUSTRIA->getName(Language::GERMAN));
        $this->assertEquals('Welt', Region::WORLD->getName(Language::GERMAN));
    }

    public function testFlagEmoji()
    {
        $this->assertEquals('🇦🇹', Region::AUSTRIA->getFlag());
        $this->assertEquals('🇩🇪', Region::GERMANY->getFlag());
        $this->assertEquals('🌎', Region::WORLD->getFlag());
    }
}
