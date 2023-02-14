<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Exception\InfiniteRecursionException;
use Blue\Core\View\Exception\InvalidComponentClassException;
use Blue\Core\View\Exception\InvalidComponentContentException;
use Blue\Core\View\Exception\InvalidComponentParameterException;
use Blue\Core\View\Helper\Functional;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\Helper\Body;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewAction;
use Blue\Core\View\ViewRenderer;
use PHPUnit\Framework\TestCase;
use stdClass;

class ViewRendererTest extends TestCase
{
    public function testShouldThrowExceptionOnInvalidComponentObject()
    {
        $this->expectException(InvalidComponentContentException::class);
        $component = Functional::include(fn() => new stdClass());
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionOnInvalidComponentClass()
    {
        $this->expectException(InvalidComponentClassException::class);
        $component = Functional::include(fn() => [static::class => []]);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionWhenCallbackReturnsInvalidType()
    {
        $this->expectException(InvalidComponentContentException::class);
        $component = Functional::include(fn() => static::class);
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testShouldThrowExceptionWhenComponentParamsIsInvalidType()
    {
        $this->expectException(InvalidComponentParameterException::class);
        $component = Functional::include(fn() => [
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
        $component = TestComponent::new();
        $component->heading = 'test';

        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('<h1>test</h1>', $renderer->render($component));
    }

    public function testShouldReplacePlaceholder()
    {
        $component = Functional::include(fn() => '{text}');
        $component->text = 'hello test';
        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('hello test', $renderer->render($component));
    }

    public function testShouldBindChildrenToParent()
    {
        $component = Functional::include(fn() => [
            Functional::include(fn($that) => [$that->text]),
            Functional::include(fn($that) => [
                $that->text,
                Functional::include(fn($that) => [$that->text])
            ])
        ]);

        $component->text = 'hello child';

        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('hello childhello childhello child', $renderer->render($component));
    }

    public function testShouldRenderNestedComponents()
    {
        $component = TestLayoutComponent::new();
        $expected = <<<EOF
<html><head><title>meta title</title></head><body><div><h1>test</h1></div></body></html>
EOF;
        $renderer = new ViewRenderer(null, true);

        $this->assertEquals($expected, $renderer->render($component));
    }

    public function testShouldRemoveAttributesFromClosingTag()
    {
        $component = Functional::include(fn() => [
            'div class="group"' => 'content',
        ]);
        $renderer = new ViewRenderer(null, true);

        $this->assertEquals('<div class="group">content</div>', $renderer->render($component));
    }

    public function testShouldRenderContentWithoutTag()
    {
        $component = Functional::include(fn() => ['content']);
        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('content', $renderer->render($component));
    }

    public function testShouldRenderClosureComponent()
    {
        $component = Functional::include(fn() => [fn() => ['h1' => 'hello world']]);

        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('<h1>hello world</h1>', $renderer->render($component));
    }

    public function testShouldAllowStringReturnFromClosureComponent()
    {
        $component = Functional::include(fn() => 'hello world');

        $renderer = new ViewRenderer(null, true);
        $this->assertEquals('hello world', $renderer->render($component));
    }

    public function testShouldInstantiateComponentClassesInKeysAndSetParams()
    {
        $component = Functional::include(fn() => [
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

        $component = Functional::include(fn() => [
            'head' => function () use (&$check) {
                return $check;
            },
            'body' => Body::include([
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
        $layout = Document::new();
        $layout->title = '';
        $layout->body = [];

        $renderer = new ViewRenderer(null, true);
        $this->assertNotEmpty($renderer->render($layout));
    }

    public function testShouldExecuteAction()
    {
        $component1 = Functional::include(
            fn(Functional $c) => "component 1" . ($c->suffix ?? '')
        );
        $component2 = Functional::include(
            function (Functional $c) use ($component1) {
                if ($c->action()->is('click')) {
                    $component1->suffix = ' hello';
                    return 'component 2 clicked';
                }
                return "component 2";
            }
        );

        $component = Functional::include(fn() => [
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
        $component = RecursiveTestComponent::new();
        $renderer = new ViewRenderer(null, true);
        $renderer->render($component);
    }

    public function testBenchmark()
    {
        $renderer = new ViewRenderer(null, true);
        $component = Document::for('title', 'description', [
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
        $component = Document::for('title', 'description', [
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
        $component = Document::for('title', 'description', [
            fn() => [
                'h1' => 'test',
                fn() => [
                    'p' => '{__id}',
                    function () {
                        $result = [];
                        for ($i = 0; $i < 100000; $i++) {
                            $result[] = TestComponent::new([TestComponent::PARAM_HEADING => '']);
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

    public function testBenchmark4()
    {
        $renderer = new ViewRenderer(null, true);
        $component = Document::for('title', 'description', [
            fn() => [
                'h1' => 'test',
                fn() => [
                    'p' => '{__id}',
                    function () {
                        $result = [];
                        for ($i = 0; $i < 100000; $i++) {
                            $result[] = [
                                'p' => 'paragraph {__id}'
                            ];
                        }

                        return $result;
                    },
                ]
            ],
        ]);
        $time = microtime(true);
        $renderer->render($component);
        $renderTime = microtime(true) - $time;

        $this->assertLessThan(1, $renderTime);
    }
}
