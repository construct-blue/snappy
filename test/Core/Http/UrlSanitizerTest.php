<?php

declare(strict_types=1);

namespace BlueTest\Core\Http;

use Blue\Core\Http\UrlSanitizer;
use PHPUnit\Framework\TestCase;

class UrlSanitizerTest extends TestCase
{
    public function testHostWithPath()
    {
        $this->assertEquals(
            'example.com/path/to/resource',
            UrlSanitizer::hostWithPath('https://example.com/path/to/resource?id=123#fragment')
        );
    }
}
