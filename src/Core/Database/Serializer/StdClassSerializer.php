<?php

declare(strict_types=1);

namespace Blue\Core\Database\Serializer;

use Blue\Core\Util\Json;

class StdClassSerializer implements SerializerInterface
{
    public function serialize(object $object)
    {
        return Json::encode($object);
    }

    public function unserialize(string $json): object
    {
        return Json::decode($json);
    }
}
