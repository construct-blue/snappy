<?php

declare(strict_types=1);

namespace Blue\Core\Database;

use Blue\Core\Environment\Environment;
use Blue\Core\Database\Exception\ConnectionInitException;
use Blue\Core\Util\MultitonTrait;
use PDO;

class Connection
{
    use MultitonTrait;

    public const MAIN = 1;
    public const SESSION = 2;
    public const TEMP = 3;
    public const ANALYTICS = 4;

    private PDO $pdo;
    private string $tablePrefix;

    public static bool $test = false;

    protected function onConstruct(): void
    {
        if (self::$test) {
            $this->initTemp();
            return;
        }
        if ($this->getInstanceId() === self::SESSION) {
            $this->initSession();
        }

        if ($this->getInstanceId() === self::ANALYTICS) {
            $this->initAnalytics();
        }

        if ($this->getInstanceId() === self::TEMP) {
            $this->initTemp();
        }
    }

    public static function main(): self
    {
        return self::instance(self::MAIN);
    }

    public static function session(): self
    {
        return self::instance(self::SESSION);
    }
    public static function analytics(): self
    {
        return self::instance(self::ANALYTICS);
    }

    public static function temp(): self
    {
        return self::instance(self::TEMP);
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    private function assertUninitialized(): void
    {
        if (isset($this->pdo)) {
            throw new ConnectionInitException('Connection ' . $this->getInstanceId() . ' already initialized');
        }
    }

    public function initMySQL(): self {
        $env = Environment::instance();
        $this->assertUninitialized();
        $this->tablePrefix = $env->get('pdo_prefix', 'blue_');
        $this->pdo = new PDO(
            $env->get('pdo_dsn', null, true),
            $env->get('pdo_username', null, true),
            $env->get('pdo_password', null, true),
            [PDO::ATTR_PERSISTENT => true]
        );
        return $this;
    }

    public function initSQLite(string $file, bool $holdConnection = false, string $tablePrefix = 'pars_'): self
    {
        $this->assertUninitialized();
        $this->tablePrefix = $tablePrefix;
        $this->pdo = new PDO(
            "sqlite:$file",
            null,
            null,
            [PDO::ATTR_PERSISTENT => $holdConnection]
        );
        return $this;
    }

    public function initTemp(): self
    {
        return $this->initSQLite(':memory:', true);
    }

    public function initSession(): self
    {
        $this->pdo = self::main()->pdo;
        $this->tablePrefix = self::main()->tablePrefix . 's_';
        return $this;
    }

    public function initAnalytics(): self
    {
        $this->pdo = self::main()->pdo;
        $this->tablePrefix = self::main()->tablePrefix;
        return $this;
    }

    public function getTablePrefix(): string
    {
        return $this->tablePrefix;
    }

    public function tableExistsCached(string $table): bool
    {
        $shouldCache = !$this->isSQLite() || !$this->isPersistent();
        if (
            $shouldCache
            && function_exists('apcu_exists')
            && function_exists('apcu_fetch')
            && function_exists('apcu_store')
        ) {
            $cacheKey = "table_state_{$this->getInstanceId()}_$table";
            if (apcu_exists($cacheKey)) {
                return apcu_fetch($cacheKey);
            }
            $result = $this->tableExists($table);
            if ($result) {
                apcu_store($cacheKey, true);
            }
            return $result;
        }
        return $this->tableExists($table);
    }

    public function isSQLite(): bool
    {
        return $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite';
    }

    public function isPersistent(): bool
    {
        return (bool)$this->pdo->getAttribute(PDO::ATTR_PERSISTENT);
    }

    public function tableExists(string $table): bool
    {
        $pdo = $this->getPDO();
        if ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
            $stmt = $pdo->prepare(
                <<<EOF
SELECT CASE WHEN 
    EXISTS(SELECT name FROM sqlite_master WHERE type='table' AND name = :table) 
    THEN 1 ELSE 0 END
EOF
            );
        } else {
            $stmt = $pdo->prepare(
                <<<EOF
SELECT CASE WHEN 
    EXISTS(SELECT * FROM information_schema.tables WHERE table_name = :table) 
    THEN 1 ELSE 0 END
EOF
            );
        }
        $stmt->execute(['table' => $table]);
        return (bool)$stmt->fetchColumn();
    }
}
