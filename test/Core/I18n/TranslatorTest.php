<?php

namespace BlueTest\Core\I18n;

use Blue\Core\I18n\Language;
use Blue\Core\I18n\Translator;

class TranslatorTest extends \PHPUnit\Framework\TestCase
{
    public function testTranslationsDe()
    {
        $translator = Translator::instance(Language::ENGLISH->toLocale());
        $translator->addPath(__DIR__ . '/examples');
        $this->assertEquals('en', $translator->translate('foo'));
    }

    public function testTranslationEn()
    {
        $translator = Translator::instance(Language::GERMAN->toLocale());
        $translator->addPath(__DIR__ . '/examples');
        $this->assertEquals('de', $translator->translate('foo'));
    }
}
