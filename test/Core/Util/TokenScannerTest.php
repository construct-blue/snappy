<?php

namespace BlueTest\Core\Util;

use Blue\Developer\Build\TokenScanner;
use PHPUnit\Framework\TestCase;

class TokenScannerTest extends TestCase
{
    public function testGetClassNameFromFile()
    {
        $scanner = new TokenScanner();
        $this->assertEquals(static::class, $scanner->getClassNameFromFile(__FILE__));
    }
}
