<?php

declare(strict_types=1);

namespace BlueTest\Core\Util;

use Blue\Core\Util\ArrayFile;
use Blue\Core\Util\Exception\ArrayFileWriteException;
use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use PHPUnit\Framework\TestCase;

class ArrayFileWriterTest extends TestCase
{
    public function testWrite()
    {
        ArrayFile::write(__DIR__ . '/data/test.php', ['test']);
        $this->assertEquals(['test'], include __DIR__ . '/data/test.php');
    }

    public function testWriteShouldCastExceptionOnInvalidFileName()
    {
        $this->expectException(ArrayFileWriteException::class);
        ArrayFile::write('', ['test']);
    }

    public function testWriteShouldCastExceptionOnInvalidData()
    {
        $this->expectException(ArrayFileWriteException::class);
        ArrayFile::write(__DIR__ . '/data/exception.php', [fopen(__FILE__, 'r')]);
    }
}
