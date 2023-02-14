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
 * @property callable $formatNumber
 * @method string formatNumber(float|int $number, string $unit, int $decimals = 0)
 */
#[Import(__DIR__ . '/Live.ts')]
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
            Document::class => [
                'title' => 'Live - NICEmobil',
                'description' => 'Das NICEmobil von Franz Liebmann online verfolgen.',
                'body' => Template::include(__DIR__ . '/Live.phtml'),
                'after' => Analytics::new()
            ]
        ];
    }
}
