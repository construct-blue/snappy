<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ViewParser;
use PHPUnit\Framework\TestCase;

class ViewParserTest extends TestCase
{
    public function testParseString()
    {
        $parser = new ViewParser();
        $this->assertEquals(
            [
                [
                    'article id="elem" class="cl"' => [
                        'content',
                        ['p' => ['paragraph']]
                    ]
                ]
            ],
            $parser->parseString('<article id="elem" class="cl">content<p>paragraph</p></article>')
        );
    }

    public function testParseString3()
    {
        $parser = new ViewParser();
        $this->assertEquals(
            [
                [
                    'p' => [
                        ['strong' => ['asdfasdf']]
                    ]
                ]
            ],
            $parser->parseString('<p><strong>asdfasdf</strong></p>')
        );
    }


    public function testParseString2()
    {
        $parser = new ViewParser();
        $this->assertEquals(
            [
                [
                    'article id="elem" class="cl"' => [
                        'content',
                        ['p' => ['paragraph']],

                    ],
                ],
                ['<br/>'],
                ['div' => ['test']]
            ],
            $parser->parseString('<article id="elem" class="cl">content<p>paragraph</p></article><br><div>test</div>')
        );
    }
}
