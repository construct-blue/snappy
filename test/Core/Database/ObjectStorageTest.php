<?php

declare(strict_types=1);

namespace BlueTest\Core\Database;

use Blue\Core\Database\Connection;
use Blue\Core\Database\ObjectStorage;
use PHPUnit\Framework\TestCase;
use stdClass;

class ObjectStorageTest extends TestCase
{
    public function testStdClass()
    {
        $connection = Connection::temp();
        $repo = new ObjectStorage(stdClass::class, 'test', 'test', $connection);
        $id = uniqid();
        $object = new stdClass();
        $object->name = 'foo';
        $repo->save($object, $id, null);

        $this->assertEquals($object, $repo->loadById($id));
    }

    public function testCustomClass()
    {
        $connection = Connection::temp();
        $repo = new ObjectStorage(TestObject::class, 'test', 'test', $connection);
        $id = uniqid();
        $object = new TestObject();
        $object->setCode('foo');
        $repo->save($object, $id, null);

        $this->assertEquals($object, $repo->loadById($id));
    }
}
