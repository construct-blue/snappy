<?php

declare(strict_types=1);

namespace Blue\Core\Cache;

use Blue\Core\Database\Connection;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Database\Serializer\SerializerInterface;
use Blue\Core\Queue\Queue;
use Closure;

class ObjectCache
{
    private ObjectStorage $storage;

    public function __construct(SerializerInterface $serializer, string $type)
    {
        $this->storage = new ObjectStorage(
            $serializer,
            $type,
            'cache',
            Connection::temp()
        );
    }

    public function load(string $id, Closure $create, Closure $check): object
    {
        if ($this->storage->existsById($id)) {
            $data = $this->storage->loadById($id);
        } else {
            $data = $create();
        }

        if ($check($data)) {
            Queue::instance()->deferTask(fn() =>  $this->storage->save($create(), $id, null));
        }

        return $data;
    }
}
