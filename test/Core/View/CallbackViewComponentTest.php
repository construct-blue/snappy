<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\CallbackViewComponent;
use Blue\Core\View\Exception\MissingPropertyException;
use PHPUnit\Framework\TestCase;

class CallbackViewComponentTest extends TestCase
{
    public function testShouldThrowExceptionWhenRenderedWithoutClosure()
    {
        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage('Missing closure');
        $component = new CallbackViewComponent();
        $component->render();
    }

    public function testShouldExecuteClosureOnRender()
    {
        $data = ['test'];
        $component = CallbackViewComponent::fromClosure(fn() => $data);
        $this->assertEquals($data, $component->render());
    }

    public function testShouldConvertScalarClosureResultToArray()
    {
        $component = CallbackViewComponent::fromClosure(fn() => 'test');
        $this->assertEquals(['test'], $component->render());
    }
}
