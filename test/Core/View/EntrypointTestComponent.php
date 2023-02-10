<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ClientScript;
use Blue\Core\View\ViewComponent;

#[ClientScript(__DIR__ . '/test/test.ts')]
class EntrypointTestComponent extends ViewComponent
{
    public function render(): array
    {
        return [];
    }
}
