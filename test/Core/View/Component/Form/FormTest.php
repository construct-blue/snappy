<?php

declare(strict_types=1);

namespace BlueTest\Core\View\Component\Form;

use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Helper\Functional;
use Blue\Core\View\ViewRenderer;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function testShouldOutputIdWhenSet()
    {
        $component = Functional::include(fn() => [
            Form::class => [
                'id' => 'id',
                'content' => ''
            ],
        ]);
        $renderer = new ViewRenderer(null, true, __DIR__);
        $this->assertEquals('<form is="reactive-form" id="id"></form>', $renderer->render($component));
    }

    public function testShouldNotOutputIdWhenNotSet()
    {
        $component = Functional::include(fn() => [
            Form::class => [
                'content' => ''
            ],
        ]);
        $renderer = new ViewRenderer(null, true, __DIR__);
        $this->assertEquals('<form is="reactive-form"></form>', $renderer->render($component));
    }

    public function testShouldAllowRemoveId()
    {
        $component = Functional::include(fn() => [
            Form::class => [
                'id' => '',
                'content' => ''
            ],
        ]);
        $renderer = new ViewRenderer(null, true, __DIR__);
        $this->assertEquals('<form is="reactive-form"></form>', $renderer->render($component, ['id' => 'id']));
    }
}
