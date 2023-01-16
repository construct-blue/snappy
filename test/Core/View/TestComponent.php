<?php

namespace BlueTest\Core\View;

use Blue\Core\View\ViewComponent;

/**
 * @property string $heading
 */
class TestComponent extends ViewComponent
{
    public const PARAM_HEADING = 'heading';

    public function render(): array
    {
        return [
            'h1' => $this->heading,
        ];
    }
}
