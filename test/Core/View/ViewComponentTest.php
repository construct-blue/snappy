<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Exception\UndefinedMethodException;
use Blue\Core\View\Exception\UndefinedPropertyException;
use Blue\Core\View\ViewRenderer;
use PHPUnit\Framework\TestCase;

class ViewComponentTest extends TestCase
{
    public function testShouldThrowExceptionWhenCallingUndefinedMethod()
    {
        $this->expectException(UndefinedMethodException::class);
        $this->expectExceptionMessage("Call to undefined method 'test' in " . TestComponent::class);
        $component = new TestComponent();
        /** @phpstan-ignore-next-line */
        $component->test();
    }

    public function testShouldThrowExceptionWhenAccessingUndefinedProperty()
    {
        $this->expectException(UndefinedPropertyException::class);
        $this->expectExceptionMessage("Access to undefined property 'heading' in " . TestComponent::class);
        $component = new TestComponent();
        /** @phpstan-ignore-next-line */
        $x = $component->heading;
    }

    public function testShouldMergePrepareDataIntoData()
    {
        $component = new TestComponent();
        $component->heading = 'heading before prepare';
        $component->text = 'text before prepare';
        $component->array = [
            'first' => 'value 1',
            'third' => 'value 3'
        ];
        $component->__prepare('', [
            'heading' => 'prepared heading',
            'array' => [
                'second' => '2',
                'third' => '3'
            ],
        ]);
        $this->assertEquals('prepared heading', $component->heading);
        $this->assertEquals('text before prepare', $component->text);
        $expectedArray = [
            'first' => 'value 1',
            'second' => '2',
            'third' => '3'
        ];
        $this->assertEquals($expectedArray, $component->array);
    }

    public function testShouldBindDataFromParentToChild()
    {
        $component1 = new TestComponent();
        $component1->heading = 'heading';
        $component2 = new TestComponent();
        $this->assertFalse(isset($component2->heading));
        $component1->__bindChild($component2);
        $this->assertTrue(isset($component2->heading));
        $this->assertEquals($component1->heading, $component2->heading);
        $component2->heading = 'second heading';
        $this->assertEquals('heading', $component1->heading);
        $this->assertEquals('second heading', $component2->heading);
    }

    public function testShouldHaveAccurateLineInfoInUndefinedPropertyException()
    {
        $component = new TemplateExceptionComponent();
        $renderer = new ViewRenderer(null, false);
        $this->assertEquals('', $renderer->render($component));
        $this->expectException(UndefinedPropertyException::class);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }
}
