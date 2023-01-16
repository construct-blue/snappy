<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ViewComponent;

class NestedTestComponent extends ViewComponent
{

    public function render(): array
    {
        return [
            'div' => [
                TestComponent::class => [
                    'heading' => 'test'
                ]
            ]
        ];
    }
}