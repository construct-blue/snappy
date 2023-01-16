<?php

declare(strict_types=1);

namespace Blue\Core\Update;

class CachedVersion
{
    private Version $version;
    private int $ttl = 86400;

    public function __construct()
    {
        $this->version = new Version();
    }

    public function getCurrent(): string
    {
        if (function_exists('apcu_entry')) {
            return apcu_entry(static::class, $this->version->getCurrent(...), $this->ttl);
        }
        return $this->version->getCurrent();
    }

    public function getLatest(): string
    {
        if (function_exists('apcu_entry')) {
            return apcu_entry(static::class, $this->version->getLatest(...), $this->ttl);
        }
        return $this->version->getLatest();
    }

    public function getLatestDownloadUrl(): string
    {
        if (function_exists('apcu_entry')) {
            return apcu_entry(static::class, $this->version->getLatestDownloadUrl(...), $this->ttl);
        }
        return $this->version->getLatestDownloadUrl();
    }

    public function isNewAvailable(): bool
    {
        if (function_exists('apcu_entry')) {
            return apcu_entry(static::class, $this->version->isNewAvailable(...), $this->ttl);
        }
        return $this->version->isNewAvailable();
    }
}
