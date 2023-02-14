<?php

namespace Blue\Models\TeslaClient;

use Blue\Core\Database\StorableObjectStorage;
use Blue\Core\Util\SingletonTrait;

class TeslaClientRepository
{
    use SingletonTrait;

    private StorableObjectStorage $storage;

    protected function onConstruct(): void
    {
        $this->storage = new StorableObjectStorage(TeslaClient::class, 'teslaClient');
        if (!$this->storage->existsByCode('nicemobil')) {
            $this->save(new TeslaClient());
        }
    }

    public function save(TeslaClient $client)
    {
        return $this->storage->save($client, $client->getId(), 'nicemobil');
    }

    public function find(): TeslaClient
    {
        return $this->storage->loadByCode('nicemobil');
    }
}
