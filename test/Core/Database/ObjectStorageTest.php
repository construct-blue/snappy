<?php

declare(strict_types=1);

namespace BlueTest\Core\Database;

use Blue\Core\Database\Connection;
use Blue\Core\Database\Exception\SerializeException;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Database\Serializer\StdClassSerializer;
use Blue\Core\Database\Serializer\StorableSerializer;
use PHPUnit\Framework\TestCase;
use stdClass;

class ObjectStorageTest extends TestCase
{
    public function testShouldThrowExceptionForMismatchedClassObject()
    {
        $this->expectException(SerializeException::class);
        $connection = Connection::temp();
        $repo = new ObjectStorage(new StorableSerializer(TestObject::class), 'test', 'test', $connection);
        $repo->save(new stdClass(), uniqid(), null);
    }


    public function testStdClass()
    {
        $connection = Connection::temp();
        $repo = new ObjectStorage(new StdClassSerializer(), 'test', 'test', $connection);
        $id = uniqid();
        $object = new stdClass();
        $object->name = 'foo';
        $repo->save($object, $id, null);

        $this->assertEquals($object, $repo->loadById($id));
    }

    public function testCustomClass()
    {
        $connection = Connection::temp();
        $repo = new ObjectStorage(new StorableSerializer(TestObject::class), 'test', 'test', $connection);
        $id = uniqid();
        $object = new TestObject();
        $object->setCode('foo');
        $repo->save($object, $id, null);

        $this->assertEquals($object, $repo->loadById($id));
    }
}
