<?php

declare(strict_types=1);

namespace Blue\Core\View\Component;

use Blue\Core\View\ViewComponent;

class SnappyLogo extends ViewComponent
{
    public function render(): array
    {
        return [
            'svg style="height: 7rem;"' => [
                '<title>Blue Snappy</title>',
                '<use href="/logo.svg#logo"/>'
            ],
        ];
    }
}
