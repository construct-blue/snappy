<?php

declare(strict_types=1);

namespace BlueTest\Core\View\Helper;

use Blue\Core\View\Exception\MissingPropertyException;
use Blue\Core\View\Helper\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public function testShouldThrowExceptionWhenTemplateIsMissing()
    {
        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage('Missing template');
        $component = Template::new();
        $component->render();
    }

    public function testShouldRenderTemplateFile()
    {
        $component = Template::include(__DIR__ . '/../TestTemplate.phtml');
        $this->assertEquals(['<h1>hello world</h1>'], $component->render());
    }
}
