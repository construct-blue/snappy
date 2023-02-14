<?php

declare(strict_types=1);

namespace Blue\Core\Database;

use Blue\Core\Database\Exception\SerializeException;
use Blue\Core\Database\Serializer\StorableSerializer;

/**
 * @template T of Storable
 */
class StorableObjectStorage extends ObjectStorage
{
    /**
     * @param class-string<T> $class
     * @param string $type
     * @param string $table
     * @param Connection|null $connection
     * @throws SerializeException
     */
    public function __construct(
        string $class,
        string $type,
        string $table = 'object',
        Connection $connection = null
    ) {
        parent::__construct(new StorableSerializer($class), $type, $table, $connection);
    }
}
