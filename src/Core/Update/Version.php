<?php

declare(strict_types=1);

namespace Blue\Core\Update;

use GuzzleHttp\Client;

class Version
{
    public function getCurrent(): string
    {
        if (file_exists('VERSION')) {
            return trim(file_get_contents('VERSION'));
        }
        return 'dev';
    }

    public function getLatest(): string
    {
        if (file_exists('VERSION') && file_exists('UPDATE_VERSION')) {
            $client = new Client();
            return trim($client->get(trim(file_get_contents('UPDATE_VERSION')))->getBody()->getContents());
        }
        return 'dev';
    }

    public function getLatestDownloadUrl(): string
    {
        if (file_exists('UPDATE_URL')) {
            return trim(file_get_contents('UPDATE_URL'));
        }
        return '';
    }

    public function compare(string $version1, string $version2): int
    {
        return version_compare($version1, $version2);
    }

    public function isNewAvailable(): bool
    {
        return $this->compare($this->getLatest(), $this->getCurrent()) > 0;
    }
}
