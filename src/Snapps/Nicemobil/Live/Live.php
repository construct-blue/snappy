<?php

declare(strict_types=1);

namespace Blue\Snapps\Nicemobil\Live;

use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;
use Blue\Logic\Client\Tesla\Entity\VehicleData;

use function number_format;

/**
 * @property VehicleData $vehicle
 * @property callable $formatNumber
 * @method string formatNumber(float|int $number, string $unit, int $decimals = 0)
 */
#[Entrypoint(__DIR__ . '/Live.ts')]
class Live extends ViewComponent
{
    protected function __init()
    {
        parent::__init();
        $this->formatNumber = function (float|int $number, string $unit, int $decimals = 0): string {
            return number_format($number, $decimals, ',', ' ') . ' ' . $unit;
        };
    }

    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Live - NICEmobil',
                'body' => TemplateViewComponent::forTemplate(__DIR__ . '/Live.phtml')
            ]
        ];
    }
}
