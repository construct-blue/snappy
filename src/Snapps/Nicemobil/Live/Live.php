<?php

declare(strict_types=1);

namespace Blue\Snapps\Nicemobil\Live;

use Blue\Core\View\Exception\InvalidComponentParameterException;
use Blue\Core\View\Import;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;
use Blue\Models\Analytics\Tracker\Client\Analytics;

use function number_format;

/**
 * @method LiveModel getModel()
 */
#[Import(__DIR__ . '/Live.ts')]
class Live extends ViewComponent
{
    protected function init()
    {
        parent::init();
        if (!$this->getModel() instanceof LiveModel) {
            throw InvalidComponentParameterException::forComponent('invalid model instance', $this);
        }
    }

    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'Live - NICEmobil',
                'description' => 'Das NICEmobil von Franz Liebmann online verfolgen.',
                'body' => Template::include(
                    __DIR__ . '/Live.phtml',
                    [
                        'online' => $this->getModel()->isOnline(),
                        'odometer' => $this->getModel()->getOdometer(),
                        'speed' => $this->getModel()->getSpeed(),
                        'power' => $this->getModel()->getPower(),
                        'latitude' => $this->getModel()->getLatitude(),
                        'longitude' => $this->getModel()->getLongitude(),
                        'batteryState' => $this->getModel()->getBatteryState(),
                        'batteryRange' => $this->getModel()->getBatteryRange(),
                        'batteryLevel' => $this->getModel()->getBatteryLevel(),
                        'outsideTemp' =>  $this->getModel()->getOutsideTemp(),
                        'insideTemp' =>  $this->getModel()->getInsideTemp(),
                        'charging' => $this->getModel()->isCharging(),
                        'fastCharger' => $this->getModel()->isFastCharger(),
                        'fastChargerType' => $this->getModel()->getFastChargerType(),
                        'chargeRate' => $this->getModel()->getChargeRate(),
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
