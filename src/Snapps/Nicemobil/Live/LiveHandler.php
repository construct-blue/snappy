<?php

namespace Blue\Snapps\Nicemobil\Live;

use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Cache\ObjectCache;
use Blue\Core\Database\Connection;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Database\Serializer\StorableSerializer;
use Blue\Core\Logger\Logger;
use Blue\Core\Queue\Queue;
use Blue\Models\TeslaClient\TeslaClientRepository;
use Blue\Models\TeslaClient\VehicleData;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class LiveHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $cache = new ObjectCache(new StorableSerializer(VehicleData::class), 'vehicle_data');
        $vehicle = $cache->load(
            'vehicle',
            fn() => $this->fetchVehicle(),
            fn(VehicleData $vehicle) => $vehicle->isOnline() || $vehicle->isExpired()
        );

        return new HtmlResponse($this->render(Live::class, ['vehicle' => $vehicle]));
    }

    private function fetchVehicle(): VehicleData
    {
        $vehicle = new VehicleData([]);
        try {
            $client = TeslaClientRepository::instance()->find();
            $vehicles = $client->getVehicles()['response'] ?? [];
            $vehicle = $client->getVehicleData($vehicles[0]['id'] ?? 0);
        } catch (Throwable $exception) {
            (new Logger())->error($exception);
        }
        return $vehicle;
    }
}
