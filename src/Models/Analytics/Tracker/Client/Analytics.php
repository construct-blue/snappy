<?php
declare(strict_types=1);

namespace Blue\Models\Analytics\Tracker\Client;

use Blue\Core\View\ClientScript;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;

#[ClientScript(__DIR__ . '/Analytics.ts')]
class Analytics extends ViewComponent
{
    public function render(): array
    {
        return [
            Template::include(__DIR__ . '/Analytics.phtml'),
        ];
    }
}