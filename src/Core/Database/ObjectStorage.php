<?php

declare(strict_types=1);

namespace Blue\Core\Database;

use Closure;
use Generator;
use stdClass;

/**
 * @template T of object
 */
class ObjectStorage
{
    private Connection $connection;

    private Closure $factory;

    private bool $setup = false;

    /**
     * @param class-string<T> $class
     * @param string $type
     * @param string $table
     * @param Connection|null $connection
     */
    public function __construct(
        private readonly string $class,
        private readonly string $type,
        private readonly string $table = 'object',
        Connection $connection = null
    ) {
        $this->connection = $connection ?? Connection::main();
        if (!$this->connection->tableExistsCached($this->getTable())) {
            $this->setup();
            $this->setup = true;
        }

        if ($this->class === stdClass::class || !is_callable([$this->class, '__set_state'])) {
            $this->factory = fn(string $object) => json_decode($object);
        } else {
            $this->factory = fn(string $object) => $this->class::__set_state(json_decode($object, true));
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

        return ($this->factory)($data);
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
        return ($this->factory)($data);
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
            yield ($this->factory)($data);
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
            yield ($this->factory)($data);
        }
    }

    public function loadCustom(Closure $closure): Generator
    {
        $pdo = $this->getConnection()->getPDO();
        $stmt = $closure($pdo, $this->getTable(), $this->getType());
        while ($data = $stmt->fetchColumn()) {
            yield ($this->factory)($data);
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
            yield ($this->factory)($object);
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
            'object' => json_encode($object),
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
