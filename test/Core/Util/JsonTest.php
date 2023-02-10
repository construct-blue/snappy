<?php
declare(strict_types=1);

namespace BlueTest\Core\Util;

use Blue\Core\Util\Exception\FileNotFoundException;
use Blue\Core\Util\Exception\FileReadException;
use Blue\Core\Util\Json;
use JsonException;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testShouldThrowException()
    {
        $this->expectException(JsonException::class);
        Json::decode('invalid json');
    }

    public function testShouldThrowFileReadException()
    {
        $this->expectException(FileReadException::class);
        Json::decodeFileAssoc(__DIR__);
    }

    public function testShouldResetErrors()
    {
        try {
            Json::decodeFileAssoc(__DIR__);
        } catch (FileReadException $exception) {
        }
        $this->assertEquals([], Json::decodeFileAssoc(__DIR__. '/test.json'));
    }

    public function testShouldThrowFileNotFoundException()
    {
        $this->expectException(FileNotFoundException::class);
        Json::decodeFileAssoc('');
    }
}
