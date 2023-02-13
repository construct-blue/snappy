<?php

declare(strict_types=1);

namespace Blue\Models\Analytics\Tracker;

use Blue\Core\Database\Connection;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Database\Serializer\StorableSerializer;
use DateTime;
use Generator;
use PDO;

class EntryRepository
{
    private ObjectStorage $storage;


    public function __construct(string $code)
    {
        $this->storage = new ObjectStorage(
            new StorableSerializer(Entry::class),
            $code,
            'analytics_entry',
            Connection::analytics()
        );
    }

    public function save(Entry $entry)
    {
        return $this->storage->save(
            $entry,
            $entry->getId(),
            $entry->getRequestId() ?: null,
            null,
            $entry->getTimestamp()
        );
    }

    public function findByRequestId(string $requestId): ?Entry
    {
        if (!$this->storage->existsByCode($requestId)) {
            return null;
        }
        return $this->storage->loadByCode($requestId);
    }

    /**
     * @return Generator&iterable<Entry>
     */
    public function findLatest(): Generator
    {
        return $this->storage->loadCustom(function (PDO $pdo, string $table, string $type) {
            $stmt = $pdo->prepare(
                <<<EOF
SELECT object 
FROM `$table` 
WHERE deleted IS NULL AND type = :type 
ORDER BY created DESC
LIMIT 5
EOF
            );
            $stmt->execute(['type' => $type]);
            return $stmt;
        });
    }

    /**
     * @return iterable<Entry>&Generator
     */
    public function findSince(int $timestamp): Generator
    {
        return $this->storage->loadCustom(function (PDO $pdo, string $table, string $type) use ($timestamp) {
            $stmt = $pdo->prepare(
                <<<EOF
SELECT object 
FROM `$table` 
WHERE deleted IS NULL AND type = :type AND created > :since
ORDER BY modified ASC
EOF
            );
            $stmt->execute([
                'type' => $type,
                'since' => date('Y-m-d H:i:s', $timestamp)
            ]);
            return $stmt;
        });
    }

    public function findByDate(DateTime $from, DateTime $to): Generator
    {
        return $this->storage->loadCustom(function (PDO $pdo, string $table, string $type) use ($from, $to) {
            $stmt = $pdo->prepare(
                <<<EOF
SELECT object 
FROM `$table` 
WHERE deleted IS NULL 
  AND type = :type 
  AND created BETWEEN :from AND :to
ORDER BY modified ASC
EOF
            );
            $stmt->execute([
                'type' => $type,
                'from' => $from->format('Y-m-d H:i:s'),
                'to' => $to->format('Y-m-d H:i:s'),
            ]);
            return $stmt;
        });
    }
}
