<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\CallbackViewComponent;
use Blue\Core\View\Exception\InvalidComponentClassException;
use Blue\Core\View\Exception\InvalidComponentContentException;
use Blue\Core\View\Exception\InvalidComponentParameterException;
use Blue\Core\View\PageViewComponent;
use Blue\Core\View\PriorityViewComponent;
use Blue\Core\View\ViewAction;
use Blue\Core\View\ViewComponent;
use Blue\Core\View\ViewRenderer;
use PHPUnit\Framework\TestCase;
use stdClass;

class ViewRendererTest extends TestCase
{
    public function testShouldThrowExceptionOnInvalidComponentObject()
    {
        $this->expectException(InvalidComponentContentException::class);
        $component = CallbackViewComponent::fromClosure(fn() => new stdClass());
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionOnInvalidComponentClass()
    {
        $this->expectException(InvalidComponentClassException::class);
        $component = CallbackViewComponent::fromClosure(fn() => [stdClass::class => []]);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionWhenCallbackReturnsInvalidType()
    {
        $this->expectException(InvalidComponentContentException::class);
        $component = CallbackViewComponent::fromClosure(fn() => stdClass::class);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionWhenComponentParamsIsInvalidType()
    {
        $this->expectException(InvalidComponentParameterException::class);
        $component = CallbackViewComponent::fromClosure(fn() => [
            TestComponent::class => ''
        ]);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldAssignIds()
    {
        $component = IdTestComponent::for([
            IdTestComponent::class => [
                'content' => [
                    'test 2',
                ]
            ],
            IdTestComponent::for([
                'test 1'
            ])
        ]);
        $renderer = new ViewRenderer();
        $this->assertEquals(
            '<div id="c-1"><div id="c-1-1">test 2</div><div id="c-1-2">test 1</div></div>',
            $renderer->render($component)
        );
    }

    public function testShouldRenderComponentToHtml()
    {
        $component = new TestComponent();
        $component->heading = 'test';

        $renderer = new ViewRenderer();
        $this->assertEquals('<h1>test</h1>', $renderer->render($component));
    }

    public function testShouldReplacePlaceholder()
    {
        $component = new class extends ViewComponent {
            public function render(): array
            {
                return [
                    '{text}'
                ];
            }
        };
        $component->text = 'hello test';
        $renderer = new ViewRenderer();
        $this->assertEquals('hello test', $renderer->render($component));
    }

    public function testShouldBindChildrenToParent()
    {
        $component = new class extends ViewComponent {
            public function render(): array
            {
                return [
                    new class extends ViewComponent {
                        public function render(): array
                        {
                            return [$this->dataFromParent];
                        }
                    },
                    new class extends ViewComponent {
                        public function render(): array
                        {
                            return [
                                $this->dataFromParent,
                                new class extends ViewComponent {
                                    public function render(): array
                                    {
                                        return [$this->dataFromParent];
                                    }
                                },
                            ];
                        }
                    }
                ];
            }
        };

        $component->dataFromParent = 'hello child';

        $renderer = new ViewRenderer();
        $this->assertEquals('hello childhello childhello child', $renderer->render($component));
    }

    public function testShouldRenderNestedComponents()
    {
        $component = new TestLayoutComponent();
        $expected = <<<EOF
<html><head><title>meta title</title></head><body><div><h1>test</h1></div></body></html>
EOF;
        $renderer = new ViewRenderer();

        $this->assertEquals($expected, $renderer->render($component));
    }

    public function testShouldRemoveAttributesFromClosingTag()
    {
        $component = new class extends ViewComponent {
            public function render(): array
            {
                return [
                    'div class="group"' => 'content',
                ];
            }
        };
        $renderer = new ViewRenderer();

        $this->assertEquals('<div class="group">content</div>', $renderer->render($component));
    }

    public function testShouldRenderContentWithoutTag()
    {
        $component = new class extends ViewComponent {
            public function render(): array
            {
                return [
                    'content',
                ];
            }
        };
        $renderer = new ViewRenderer();
        $this->assertEquals('content', $renderer->render($component));
    }

    public function testShouldRenderClosureComponent()
    {
        $component = new class extends ViewComponent {
            public function render(): array
            {
                return [
                    fn() => ['h1' => 'hello world']
                ];
            }
        };

        $renderer = new ViewRenderer();
        $this->assertEquals('<h1>hello world</h1>', $renderer->render($component));
    }

    public function testShouldAllowStringReturnFromClosureComponent()
    {
        $component = new class extends ViewComponent {
            public function render(): array
            {
                return [
                    function () {
                        return 'hello world';
                    }
                ];
            }
        };

        $renderer = new ViewRenderer();
        $this->assertEquals('hello world', $renderer->render($component));
    }

    public function testShouldInstantiateComponentClassesInKeysAndSetParams()
    {
        $component = CallbackViewComponent::fromClosure(fn() => [
            TestComponent::class => [
                TestComponent::PARAM_HEADING => 'test heading'
            ]
        ]);

        $renderer = new ViewRenderer();
        $this->assertEquals('<h1>test heading</h1>', $renderer->render($component));
    }

    public function testShouldRenderPriorityComponentFirst()
    {
        $check = '';
        $renderer = new ViewRenderer();

        $component = CallbackViewComponent::fromClosure(fn() => [
            'head' => function () use (&$check) {
                return $check;
            },
            'body' => PriorityViewComponent::from([
                function () use (&$check) {
                    $check = 'hello from body';
                    return '';
                }
            ])
        ]);
        $this->assertEquals("<head>hello from body</head><body></body>", $renderer->render($component));
    }

    public function testShouldOutputStylesAndScriptsToLayout()
    {
        $layout = new PageViewComponent();
        $layout->title = '';
        $layout->body = [];

        $renderer = new ViewRenderer();
        $this->assertNotEmpty($renderer->render($layout));
    }

    public function testShouldExecuteAction()
    {
        $component1 = CallbackViewComponent::fromClosure(
            fn(CallbackViewComponent $c) => "component 1" . ($c->suffix ?? '')
        );
        $component2 = CallbackViewComponent::fromClosure(
            function (CallbackViewComponent $c) use ($component1) {
                if ($c->getAction()->is('click')) {
                    $component1->suffix = ' hello';
                    return 'component 2 clicked';
                }
                return "component 2";
            }
        );

        $component = CallbackViewComponent::fromClosure(fn() => [
            $component1,
            ' ',
            $component2,
        ]);

        $renderer = new ViewRenderer();
        $this->assertEquals('component 1 component 2', $renderer->render($component));
        $renderer->action($component, new ViewAction('click'));
        $this->assertEquals('component 1 hello component 2 clicked', $renderer->render($component));
    }
}
