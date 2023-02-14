<?php

declare(strict_types=1);

namespace BlueTest\Core\View\Helper;

use Blue\Core\View\Exception\MissingPropertyException;
use Blue\Core\View\Helper\Functional;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function testShouldThrowExceptionWhenRenderedWithoutClosure()
    {
        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage('Missing closure');
        $component = Functional::new();
        $component->render();
    }

    public function testShouldExecuteClosureOnRender()
    {
        $data = ['test'];
        $component = Functional::include(fn() => $data);
        $this->assertEquals($data, $component->render());
    }

    public function testShouldConvertScalarClosureResultToArray()
    {
        $component = Functional::include(fn() => 'test');
        $this->assertEquals(['test'], $component->render());
    }
}
