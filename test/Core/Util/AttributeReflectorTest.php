<?php
declare(strict_types=1);

namespace BlueTest\Core\Util;

use Blue\Core\Util\AttributeReflector;
use PHPUnit\Framework\TestCase;

class AttributeReflectorTest extends TestCase
{
    public function testShouldFindAttributesOfBaseClasses()
    {
        $attributes = AttributeReflector::getAttributes(TestClass::class, TestAttribute::class);
        $this->assertEquals([
            new TestAttribute('base'),
            new TestAttribute('test'),
        ], $attributes);
    }
}
