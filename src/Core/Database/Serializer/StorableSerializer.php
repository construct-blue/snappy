<?php

declare(strict_types=1);

namespace Blue\Core\Database\Serializer;

use Blue\Core\Database\Exception\SerializeException;
use Blue\Core\Database\Storable;
use Blue\Core\Util\Json;

class StorableSerializer implements SerializerInterface
{
    public function __construct(private readonly string $class)
    {
        if (!in_array(Storable::class, class_implements($class))) {
            throw new SerializeException('Storage object class must implement ' . Storable::class);
        }
    }

    public function serialize(object $object)
    {
        if (!$object instanceof $this->class) {
            throw new SerializeException(
                'Expected object of type ' . $this->class . ', ' . get_debug_type($object) . ' given'
            );
        }
        return Json::encode($object->toStorage());
    }

    public function unserialize(string $json): object
    {
        /** @var class-string<Storable> $class */
        $class = $this->class;
        return $class::fromStorage(Json::decodeAssoc($json));
    }
}