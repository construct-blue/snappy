<?php

namespace BlueTest\Core\View;

use Blue\Core\View\ViewComponent;

class TestLayoutComponent extends ViewComponent
{
    public function render(): array
    {
        return [
            'html' => [
                'head' => [
                    'title' => 'meta title'
                ],
                'body' => new NestedTestComponent(),
            ]
        ];
    }
}