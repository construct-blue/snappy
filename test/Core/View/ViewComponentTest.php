<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Exception\UndefinedPropertyException;
use Blue\Core\View\Helper\Functional;
use Blue\Core\View\ViewModel;
use Blue\Core\View\ViewRenderer;
use PHPUnit\Framework\TestCase;

class ViewComponentTest extends TestCase
{
    public function testShouldThrowExceptionWhenAccessingUndefinedProperty()
    {
        $this->expectException(UndefinedPropertyException::class);
        $this->expectExceptionMessage("Access to undefined property 'heading' in " . TestComponent::class);
        $component = TestComponent::new();
        /** @phpstan-ignore-next-line */
        $x = $component->heading;
    }

    public function testShouldMergePrepareDataIntoData()
    {
        $component = TestComponent::new();
        $component->heading = 'heading before prepare';
        $component->text = 'text before prepare';
        $component->array = [
            'first' => 'value 1',
            'third' => 'value 3'
        ];
        $component->prepare('', [
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

    public function testShouldHaveAccurateLineInfoInUndefinedPropertyException()
    {
        $component = TemplateExceptionComponent::new();
        $renderer = new ViewRenderer(null, false);
        $this->assertEquals('', $renderer->render($component));

        $this->expectException(UndefinedPropertyException::class);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }


    public function testShouldOverrideExistingParamsOnPrepare()
    {
        $component = Functional::include(fn($c) => [$c->value]);
        $component->value = 'initial';

        $component->prepare('', [
            'value' => 'override'
        ]);

        $renderer = new ViewRenderer(null, true);
        $result = $renderer->render($component);
        $this->assertEquals('override', $result);
    }

    public function testShouldNotInheritDataFromParent()
    {
        $parent = Functional::include(fn() => [
            Functional::include(fn($c) => $c->value ?? '')
        ]);
        $parent->value = 'parent';
        $renderer = new ViewRenderer(null, true);
        $result = $renderer->render($parent);
        $this->assertEquals('', $result);
    }

    public function testShouldInitDefaultModel()
    {
        $component = Functional::include(fn() => []);
        $this->assertInstanceOf(ViewModel::class, $component->getModel());
    }

    public function testShouldBeAbleToInitWithCustomModel()
    {
        $component = TestComponent::new(new StubModel());
        $this->assertInstanceOf(StubModel::class, $component->getModel());
    }
}
