<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ViewModelInterface;

class StubModel implements ViewModelInterface
{
    public function get(string $key): mixed
    {
        return null;
    }

    public function set(string $key, mixed $value): void
    {

    }

    public function setDefault(string $key, mixed $value): void
    {

    }

    public function replaceValues(array $params): void
    {
    }
}