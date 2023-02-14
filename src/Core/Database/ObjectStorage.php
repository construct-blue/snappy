<?php

declare(strict_types=1);

namespace Blue\Core\Database;

use Blue\Core\Database\Serializer\SerializerInterface;
use Closure;
use Generator;

/**
 * @template T of object
 */
class ObjectStorage
{
    private Connection $connection;

    private bool $setup = false;

    /**
     *
     * @param SerializerInterface $serializer
     * @param string $type
     * @param string $table
     * @param Connection|null $connection
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly string $type,
        private readonly string $table = 'object',
        Connection $connection = null
    ) {
        $this->connection = $connection ?? Connection::main();
        if (!$this->connection->tableExistsCached($this->getTable())) {
            $this->setup();
            $this->setup = true;
        }
    }

    private function getTable(): string
    {
        return $this->connection->getTablePrefix() . $this->table;
    }

    private function getType(): string
    {
        return $this->type;
    }

    private function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @return bool
     */
    public function isSetup(): bool
    {
        return $this->setup;
    }

    private function setup(): void
    {
        $pdo = $this->getConnection()->getPDO();
        $pdo->exec(
            <<<SQL
CREATE TABLE `{$this->getTable()}`
(
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    code VARCHAR(255) DEFAULT NULL UNIQUE,
    type VARCHAR(255) NOT NULL,
    object JSON,
    meta JSON,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT NULL,
    deleted TIMESTAMP DEFAULT NULL
)
SQL
        );
    }

    /**
     * @param string $id
     * @return T
     * @throws Exception\ObjectLoadingException
     */
    public function loadById(string $id): object
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT object
FROM `{$this->getTable()}`
WHERE deleted IS NULL AND id = :id
SQL
        );
        $stmt->execute([
            'id' => $id
        ]);
        $data = $stmt->fetchColumn();
        if (empty($data)) {
            throw new Exception\ObjectLoadingException('Unable to load object with id ' . $id);
        }

        return $this->serializer->unserialize($data);
    }

    /**
     * @param string $code
     * @return T
     * @throws Exception\ObjectLoadingException
     */
    public function loadByCode(string $code): object
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT object
FROM `{$this->getTable()}`
WHERE deleted IS NULL AND code = :code
SQL
        );
        $stmt->execute([
            'code' => $code
        ]);
        $data = $stmt->fetchColumn();
        if (empty($data)) {
            throw new Exception\ObjectLoadingException('Unable to load object with code ' . $code);
        }
        return $this->serializer->unserialize($data);
    }

    public function loadCodes(): Generator
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT code
FROM `{$this->getTable()}`
WHERE deleted IS NULL AND code IS NOT NULL
SQL
        );
        $stmt->execute();
        while ($code = $stmt->fetchColumn()) {
            yield $code;
        }
    }

    public function loadIds(): Generator
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT id
FROM `{$this->getTable()}`
WHERE deleted IS NULL
SQL
        );
        $stmt->execute();
        while ($id = $stmt->fetchColumn()) {
            yield $id;
        }
    }

    /**
     * @return Generator&iterable<T>
     */
    public function loadAll(): Generator
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT object
FROM `{$this->getTable()}`
WHERE deleted IS NULL AND type = :type
SQL
        );
        $stmt->execute([
            'type' => $this->getType()
        ]);

        while ($data = $stmt->fetchColumn()) {
            yield $this->serializer->unserialize($data);
        }
    }

    /**
     * @param int $limit
     * @param int $page
     * @return Generator&iterable<T>
     */
    public function loadLimit(int $limit, int $page = 1): Generator
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT object
FROM `{$this->getTable()}`
WHERE deleted IS NULL AND type = :type LIMIT :offset, :limit
SQL
        );
        $stmt->execute([
            'type' => $this->getType(),
            'limit' => abs($limit),
            'offset' => abs($limit * ($page - 1))
        ]);

        while ($data = $stmt->fetchColumn()) {
            yield $this->serializer->unserialize($data);
        }
    }

    public function loadCustom(Closure $closure): Generator
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $closure($pdo, $this->getTable(), $this->getType());
        while ($data = $stmt->fetchColumn()) {
            yield $this->serializer->unserialize($data);
        }
    }

    /**
     * @return Generator&iterable<T>
     */
    public function loadDeleted(): Generator
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT object
FROM `{$this->getTable()}`
WHERE deleted IS NOT NULL AND type = :type
SQL
        );
        $stmt->execute([
            'type' => $this->getType()
        ]);

        while ($object = $stmt->fetchColumn()) {
            yield $this->serializer->unserialize($object);
        }
    }

    public function existsById(string $id): bool
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT CASE WHEN
    EXISTS(SELECT NULL FROM `{$this->getTable()}` WHERE deleted IS NULL AND id = :id)
THEN 1 ELSE 0 END
SQL
        );
        $stmt->execute([
            'id' => $id
        ]);
        return (bool)$stmt->fetchColumn();
    }

    public function existsByCode(string $code): bool
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
SELECT CASE WHEN
    EXISTS(SELECT NULL FROM `{$this->getTable()}` WHERE deleted IS NULL AND code = :code)
THEN 1 ELSE 0 END
SQL
        );
        $stmt->execute([
            'code' => $code
        ]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * @param T $object
     * @param string $id
     * @param string|null $code
     * @param array|null $meta
     * @param int|null $created
     * @return bool
     */
    public function save(object $object, string $id, ?string $code, array $meta = null, int $created = null): bool
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
REPLACE INTO `{$this->getTable()}` (created, modified, id, code, type, object, meta)
    VALUES (:created, CURRENT_TIMESTAMP, :id, :code, :type, :object, :meta)
SQL
        );
        return $stmt->execute([
            'id' => $id,
            'code' => $code,
            'created' => $created ? date('Y-m-d H:i:s', $created) : null,
            'type' => $this->getType(),
            'object' => $this->serializer->serialize($object),
            'meta' => json_encode($meta)
        ]);
    }

    public function delete(string $id): bool
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $pdo->prepare(
            <<<SQL
UPDATE `{$this->getTable()}` 
SET code = NULL, deleted = CURRENT_TIMESTAMP WHERE id = :id
SQL
        );
        return $stmt->execute([
            'id' => $id
        ]);
    }
}
