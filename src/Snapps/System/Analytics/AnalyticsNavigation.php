<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Analytics;

use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

class AnalyticsNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                Link::class => [
                    'href' => '{basePath}/day',
                    'text' => 'Day',
                    'active' => true,
                ],
            ],
        ];
    }
}
