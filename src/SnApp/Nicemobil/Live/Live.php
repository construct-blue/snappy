<?php

declare(strict_types=1);

namespace Blue\SnApp\Nicemobil\Live;

use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageViewComponent;
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
    protected function init()
    {
        parent::init();
        $this->formatNumber = function (float|int $number, string $unit, int $decimals = 0): string {
            return number_format($number, $decimals, ',', ' ') . ' ' . $unit;
        };
    }

    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Live - NICEmobil',
                'body' => TemplateViewComponent::forTemplate(__DIR__ . '/Live.phtml')
            ]
        ];
    }
}
