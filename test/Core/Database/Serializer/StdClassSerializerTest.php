<?php
declare(strict_types=1);

namespace BlueTest\Core\Database\Serializer;

use Blue\Core\Database\Serializer\StdClassSerializer;
use PHPUnit\Framework\TestCase;
use stdClass;

class StdClassSerializerTest extends TestCase
{
    public function testSerialize()
    {
        $object = new stdClass();
        $object->code = 'test';

        $this->assertEquals(
            '{"code":"test"}',
            (new StdClassSerializer())->serialize($object)
        );
    }

    public function testUnserialize()
    {
        $object = new stdClass();
        $object->code = 'test';

        $this->assertEquals(
            $object,
            (new StdClassSerializer())->unserialize('{"code":"test"}')
        );
    }
}
