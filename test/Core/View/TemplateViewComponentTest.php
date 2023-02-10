<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Exception\MissingPropertyException;
use Blue\Core\View\Helper\Template;
use PHPUnit\Framework\TestCase;

class TemplateViewComponentTest extends TestCase
{
    public function testShouldThrowExceptionWhenTemplateIsMissing()
    {
        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage('Missing template');
        $component = new Template();
        $component->render();
    }

    public function testShouldRenderTemplateFile()
    {
        $component = Template::include(__DIR__ . '/TestTemplate.phtml');
        $this->assertEquals(['<h1>hello world</h1>'], $component->render());
    }
}
