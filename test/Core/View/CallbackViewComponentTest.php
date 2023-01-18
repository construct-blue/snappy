<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ClosureView;
use Blue\Core\View\Exception\MissingPropertyException;
use PHPUnit\Framework\TestCase;

class CallbackViewComponentTest extends TestCase
{
    public function testShouldThrowExceptionWhenRenderedWithoutClosure()
    {
        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage('Missing closure');
        $component = new ClosureView();
        $component->render();
    }

    public function testShouldExecuteClosureOnRender()
    {
        $data = ['test'];
        $component = ClosureView::from(fn() => $data);
        $this->assertEquals($data, $component->render());
    }

    public function testShouldConvertScalarClosureResultToArray()
    {
        $component = ClosureView::from(fn() => 'test');
        $this->assertEquals(['test'], $component->render());
    }
}
