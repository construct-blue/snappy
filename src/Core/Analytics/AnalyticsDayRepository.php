<?php

declare(strict_types=1);

namespace Blue\Core\Analytics;

use Generator;
use Blue\Core\Database\Connection;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Util\SingletonTrait;
use PDO;

class AnalyticsDayRepository
{
    use SingletonTrait;

    private ObjectStorage $storage;

    protected function onConstruct(): void
    {
        $this->storage = new ObjectStorage(Day::class, 'default', 'analytics_day', Connection::analytics());
    }

    public function save(Day $day)
    {
        $this->storage->save($day, $day->getId(), $day->getCode());
    }

    public function findByCode(string $code): Day
    {
        return $this->storage->loadByCode($code);
    }

    public function findAllCodes(): Generator
    {
        return $this->storage->loadCodes();
    }

    public function findToday(): Day
    {
        $day = new Day();
        if ($this->existsByCode($day->getCode())) {
            return $this->findByCode($day->getCode());
        }
        return $day;
    }

    public function existsByCode(string $code): bool
    {
        return $this->storage->existsByCode($code);
    }
}
