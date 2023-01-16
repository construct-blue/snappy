<?php

declare(strict_types=1);

namespace Blue\Core\Analytics;

use Generator;
use Blue\Core\Database\Connection;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Util\SingletonTrait;
use PDO;

class AnalyticsEntryRepository
{
    use SingletonTrait;

    private ObjectStorage $storage;


    protected function onConstruct(): void
    {
        $this->storage = new ObjectStorage(Entry::class, 'default', 'analytics_entry', Connection::analytics());
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
SELECT * 
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
SELECT * 
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
}
