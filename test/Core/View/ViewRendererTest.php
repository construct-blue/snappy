<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ClosureView;
use Blue\Core\View\Exception\InfiniteRecursionException;
use Blue\Core\View\Exception\InvalidComponentClassException;
use Blue\Core\View\Exception\InvalidComponentContentException;
use Blue\Core\View\Exception\InvalidComponentParameterException;
use Blue\Core\View\Helper\PageWrapper;
use Blue\Core\View\Helper\RenderFirst;
use Blue\Core\View\Helper\Template;
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
        $component = ClosureView::from(fn() => new stdClass());
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionOnInvalidComponentClass()
    {
        $this->expectException(InvalidComponentClassException::class);
        $component = ClosureView::from(fn() => [static::class => []]);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionWhenCallbackReturnsInvalidType()
    {
        $this->expectException(InvalidComponentContentException::class);
        $component = ClosureView::from(fn() => static::class);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionWhenComponentParamsIsInvalidType()
    {
        $this->expectException(InvalidComponentParameterException::class);
        $component = ClosureView::from(fn() => [
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
        $renderer = new ViewRenderer(null, true);
        $this->assertEquals(
            '<div id="c-1"><div id="c-1-1">test 2</div><div id="c-1-2">test 1</div></div>',
            $renderer->render($component)
        );
    }

    public function testShouldRenderComponentToHtml()
    {
        $component = new TestComponent();
        $component->heading = 'test';

        $renderer = new ViewRenderer(null, true);
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
        $renderer = new ViewRenderer(null, true);
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

        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('hello childhello childhello child', $renderer->render($component));
    }

    public function testShouldRenderNestedComponents()
    {
        $component = new TestLayoutComponent();
        $expected = <<<EOF
<html><head><title>meta title</title></head><body><div><h1>test</h1></div></body></html>
EOF;
        $renderer = new ViewRenderer(null, true);

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
        $renderer = new ViewRenderer(null, true);

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
        $renderer = new ViewRenderer(null, true);
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

        $renderer = new ViewRenderer(null, true);
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

        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('hello world', $renderer->render($component));
    }

    public function testShouldInstantiateComponentClassesInKeysAndSetParams()
    {
        $component = ClosureView::from(fn() => [
            TestComponent::class => [
                TestComponent::PARAM_HEADING => 'test heading'
            ]
        ]);

        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('<h1>test heading</h1>', $renderer->render($component));
    }

    public function testShouldRenderPriorityComponentFirst()
    {
        $check = '';
        $renderer = new ViewRenderer(null, true);

        $component = ClosureView::from(fn() => [
            'head' => function () use (&$check) {
                return $check;
            },
            'body' => RenderFirst::from([
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
        $layout = new PageWrapper();
        $layout->title = '';
        $layout->body = [];

        $renderer = new ViewRenderer(null, true);
        $this->assertNotEmpty($renderer->render($layout));
    }

    public function testShouldExecuteAction()
    {
        $component1 = ClosureView::from(
            fn(ClosureView $c) => "component 1" . ($c->suffix ?? '')
        );
        $component2 = ClosureView::from(
            function (ClosureView $c) use ($component1) {
                if ($c->action()->is('click')) {
                    $component1->suffix = ' hello';
                    return 'component 2 clicked';
                }
                return "component 2";
            }
        );

        $component = ClosureView::from(fn() => [
            $component1,
            ' ',
            $component2,
        ]);

        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('component 1 component 2', $renderer->render($component));
        $renderer->action($component, new ViewAction('click'));
        $this->assertEquals('component 1 hello component 2 clicked', $renderer->render($component));
    }

    public function testShouldThrowExceptionWhenRecursiveNesting()
    {
        $this->expectException(InfiniteRecursionException::class);
        $component = new RecursiveTestComponent();
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testBenchmark()
    {
        $renderer = new ViewRenderer(null, true);
        $component = PageWrapper::for('title', 'description', [
            fn() => [
                'h1' => 'test',
                fn() => [
                    'p' => '{__id}',
                    function () {
                        $result = [];
                        for ($i = 0; $i < 100000; $i++) {
                            $result[] = fn() => ['p' => '{__id}'];
                        }

                        return $result;
                    },
                ]
            ],
        ]);
        $time = microtime(true);
        $renderer->render($component);
        $renderTime = microtime(true) - $time;

        $this->assertLessThan(5, $renderTime);
    }

    public function testBenchmark2()
    {
        $renderer = new ViewRenderer(null, true);
        $component = PageWrapper::for('title', 'description', [
            fn() => [
                'h1' => 'test',
                fn() => [
                    'p' => '{__id}',
                    function () {
                        $result = [];
                        for ($i = 0; $i < 100000; $i++) {
                            $result[] = Template::include(__DIR__ . '/TestTemplate.phtml');
                        }

                        return $result;
                    },
                ]
            ],
        ]);
        $time = microtime(true);
        $renderer->render($component);
        $renderTime = microtime(true) - $time;

        $this->assertLessThan(10, $renderTime);
    }


    public function testBenchmark3()
    {
        $renderer = new ViewRenderer(null, true);
        $component = PageWrapper::for('title', 'description', [
            fn() => [
                'h1' => 'test',
                fn() => [
                    'p' => '{__id}',
                    function () {
                        $result = [];
                        for ($i = 0; $i < 100000; $i++) {
                            $result[] = TestComponent::create([TestComponent::PARAM_HEADING => '']);
                        }

                        return $result;
                    },
                ]
            ],
        ]);
        $time = microtime(true);
        $renderer->render($component);
        $renderTime = microtime(true) - $time;

        $this->assertLessThan(5, $renderTime);
    }
}
