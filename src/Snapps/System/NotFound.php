<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Core\View\ViewComponent;

class NotFound extends ViewComponent
{
    public function render(): array
    {
        return [
            \Blue\Core\Application\Error\NotFound\NotFound::class => [
                'header' => new SystemNavigation()
            ],
        ];
    }
}
