<?php

namespace Blue\Snapps\Nicemobil\Live;

use Blue\Core\Application\Handler\TemplateHandler;
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
        $vehicle = new VehicleData([]);

        try {
            $storage = new ObjectStorage(new StorableSerializer(VehicleData::class), 'vehicle_data', 'nicemobil', Connection::temp());
            if ($storage->existsById('vehicle')) {
                /** @var VehicleData $vehicle */
                $vehicle = $storage->loadById('vehicle');
                if ($vehicle->isExpired()) {
                    $storage->delete('vehicle');
                }
            } else {
                $vehicle = $this->fetchVehicle();
            }

            if (!$vehicle->isOnline() || $vehicle->isExpired()) {
                Queue::instance()->deferTask(fn() => $storage->save($this->fetchVehicle(), 'vehicle', null));
            }
        } catch (Throwable $exception) {
            (new Logger())->error($exception);
        }
        return new HtmlResponse($this->render(Live::class, ['vehicle' => $vehicle]));
    }

    private function fetchVehicle(): VehicleData
    {
        $client = TeslaClientRepository::instance()->find();
        $vehicles = $client->getVehicles()['response'] ?? [];
        return $client->getVehicleData($vehicles[0]['id'] ?? 0);
    }
}
