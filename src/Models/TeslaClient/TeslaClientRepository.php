<?php

namespace Blue\Models\TeslaClient;

use Blue\Core\Database\ObjectStorage;
use Blue\Core\Database\Serializer\StorableSerializer;
use Blue\Core\Util\SingletonTrait;

class TeslaClientRepository
{
    use SingletonTrait;

    private ObjectStorage $storage;

    protected function onConstruct(): void
    {
        $this->storage = new ObjectStorage(new StorableSerializer(TeslaClient::class), 'teslaClient');
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
