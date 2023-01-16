<?php

declare(strict_types=1);

namespace Blue\Core\Update;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Updater
{
    private Version $version;

    public function __construct()
    {
        $this->version = new Version();
    }

    public function getVersion(): Version
    {
        return $this->version;
    }

    public function downloadLatest(string $filePath): bool
    {
        if (!$this->getVersion()->getLatestDownloadUrl()) {
            return false;
        }
        $client = new Client();
        $client->get($this->getVersion()->getLatestDownloadUrl(), [
            RequestOptions::SINK => $filePath
        ]);

        return true;
    }
}
