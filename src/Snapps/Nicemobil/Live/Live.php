<?php

declare(strict_types=1);

namespace Blue\Snapps\Nicemobil\Live;

use Blue\Core\View\Import;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;
use Blue\Models\Analytics\Tracker\Client\Analytics;
use Blue\Models\TeslaClient\VehicleData;

use function number_format;

/**
 * @property VehicleData $vehicle
 */
#[Import(__DIR__ . '/Live.ts')]
class Live extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'Live - NICEmobil',
                'description' => 'Das NICEmobil von Franz Liebmann online verfolgen.',
                'body' => Template::include(
                    __DIR__ . '/Live.phtml',
                    [
                        'online' => $this->vehicle->isOnline(),
                        'odometer' => $this->formatNumber($this->vehicle->getOdometerKM(), 'km'),
                        'speed' => $this->formatNumber($this->vehicle->getSpeedKMh(), 'km/h'),
                        'power' => $this->formatNumber($this->vehicle->getPower(), 'kW'),
                        'latitude' => $this->vehicle->getLatitude(),
                        'longitude' => $this->vehicle->getLongitude(),
                        'batteryState' => $this->vehicle->getChargeLevelCategory(),
                        'batteryRange' => $this->formatNumber($this->vehicle->getIdealBatteryRangeKM(), 'km'),
                        'batteryLevel' => $this->vehicle->getUsableBatteryLevel(),
                        'outsideTemp' =>  $this->formatNumber($this->vehicle->getOutsideTemp(), '°C', 1),
                        'insideTemp' =>  $this->formatNumber($this->vehicle->getInsideTemp(), '°C', 1),
                        'charging' => $this->vehicle->isCharging(),
                        'fastCharger' => $this->vehicle->isFastChargerPresent(),
                        'fastChargerType' => $this->vehicle->getFastChargerType(),
                        'chargeRate' => $this->formatNumber($this->vehicle->getChargeRateKMh(), 'km/h'),
                    ]
                ),
                'after' => Analytics::new()
            ]
        ];
    }

    private function formatNumber(float|int $number, string $unit, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', ' ') . ' ' . $unit;
    }
}
