<?php

declare(strict_types=1);

namespace BlueTest\Core\Exception;

use Error;
use Blue\Core\Exception\CoreException;
use Blue\Core\Exception\InvalidCastError;
use PHPUnit\Framework\TestCase;
use stdClass;

class CoreExceptionTest extends TestCase
{
    public function testShouldThrowErrorWhenCastingToNonException()
    {
        $this->expectException(InvalidCastError::class);
        $exception = new CoreException('test');
        $exception->castTo(stdClass::class);
    }

    public function testShouldCastToOtherExceptionClass()
    {
        $exception = new CoreException('test');
        $result = $exception->castTo(TestException::class);
        $this->assertInstanceOf(TestException::class, $result);
    }

    public function testCreateFromThrowable()
    {
        $throwable = new Error('test');
        $result = TestException::from($throwable);
        $this->assertInstanceOf(TestException::class, $result);
    }
}
