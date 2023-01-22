<?php

declare(strict_types=1);

namespace BlueTest\Core\Application;

use Blue\Core\Application\SnappCode;
use PHPUnit\Framework\TestCase;

class SnappCodeTest extends TestCase
{
    public function testBuild()
    {
        $this->assertEquals('', SnappCode::build(null, null));
        $this->assertEquals('www-example-com', SnappCode::build('www.example.com', null));
        $this->assertEquals('www-example-com-page', SnappCode::build('www.example.com', '/page/'));
        $this->assertEquals('page', SnappCode::build(null, '/page/'));
    }
}
