<?php
declare(strict_types=1);

namespace BlueTest\Core\Database\Serializer;

use Blue\Core\Database\Serializer\StorableSerializer;
use BlueTest\Core\Database\TestObject;
use PHPUnit\Framework\TestCase;

class StorableSerializerTest extends TestCase
{
    public function testSerialize()
    {
        $object = new TestObject();
        $object->setCode('test');

        $this->assertEquals(
            '{"code":"test"}',
            (new StorableSerializer(TestObject::class))->serialize($object)
        );
    }

    public function testUnserialize()
    {
        $object = new TestObject();
        $object->setCode('test');

        $this->assertEquals(
            $object,
            (new StorableSerializer(TestObject::class))->unserialize('{"code":"test"}')
        );
    }
}
