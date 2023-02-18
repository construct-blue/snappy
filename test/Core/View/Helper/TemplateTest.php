<?php

declare(strict_types=1);

namespace BlueTest\Core\View\Helper;

use Blue\Core\View\Exception\MissingPropertyException;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewModel;
use Blue\Core\View\ViewRenderer;
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

    public function testShouldAssignTemplateVars()
    {
        $component = Template::include(
            __DIR__ . '/TemplateVarTemplate.phtml',
            ['heading' => 'hello from template var']
        );
        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('<h1>hello from template var</h1>', trim($renderer->render($component)));
    }

    public function testShouldAssignModelAsTemplateVar()
    {
        $component = Template::include(
            __DIR__ . '/ModelTemplate.phtml',
            [],
            new ViewModel(['heading' => 'hello from template var'])
        );
        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('<h1>hello from template var</h1>', trim($renderer->render($component)));
    }
}
