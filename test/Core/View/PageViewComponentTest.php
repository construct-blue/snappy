<?php

namespace BlueTest\Core\View;

use Blue\Core\View\ClientResources;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewRenderer;
use PHPUnit\Framework\TestCase;
use ReflectionObject;

class PageViewComponentTest extends TestCase
{
    public function testRender()
    {
        $component = new PageWrapper();
        $component->body = [];
        $component->title = '';
        $renderer = new ViewRenderer(null, true, __DIR__);

        $this->assertEquals(
            '<!DOCTYPE html><html lang="en"><head><title></title><meta name="viewport" content="width=device-width, initial-scale=1" /><meta charset="UTF-8"><link rel="icon" href="/favicon.ico" sizes="any"><link rel="apple-touch-icon" href="/apple-touch-icon.png"><link rel="manifest" href="/manifest.webmanifest"></head><body></body></html>',
            $renderer->render($component)
        );
    }
}
