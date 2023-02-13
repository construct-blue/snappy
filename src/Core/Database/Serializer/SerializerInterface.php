<?php

declare(strict_types=1);

namespace Blue\Core\Database\Serializer;

interface SerializerInterface
{
    public function serialize(object $object);

    public function unserialize(string $json): object;
}
