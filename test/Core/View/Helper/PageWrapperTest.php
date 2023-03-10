<?php

namespace BlueTest\Core\View\Helper;

use Blue\Core\View\Helper\Document;
use Blue\Core\View\ViewRenderer;
use PHPUnit\Framework\TestCase;

class PageWrapperTest extends TestCase
{
    public function testRender()
    {
        $component = Document::new();
        $component->body = [];
        $component->title = '';
        $renderer = new ViewRenderer(null, true);

        $this->assertEquals(
            '<!DOCTYPE html><html lang="en"><head><title></title><meta name="viewport" content="width=device-width, initial-scale=1"/><meta charset="UTF-8"><link rel="icon" href="/favicon.ico" sizes="any"><link rel="apple-touch-icon" href="/apple-touch-icon.png"><link rel="manifest" href="/manifest.webmanifest"></head><body><info><icon><svg><use href="/icons.svg#alert-circle"/></svg></icon> Development Mode</info></body></html>',
            str_replace("\n", '', $renderer->render($component))
        );
    }
}
