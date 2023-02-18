<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ViewComponent;

class CustomModelTestComponent extends ViewComponent
{
    protected function init()
    {
        parent::init();
        $this->assertModel(StubModel::class);
    }

    public function render(): array
    {
        return [];
    }
}