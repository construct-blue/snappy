<?php

declare(strict_types=1);

namespace Blue\Snapps\Analytics;

use Blue\Core\View\ViewComponent;

class AnalyticsNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                'a href="/"' => 'Home',
                'a href="{basePath}/day"' => 'Day',
            ],
        ];
    }
}
