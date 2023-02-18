<?php
declare(strict_types=1);

namespace Blue\Core\View;

interface ViewModelInterface
{
    public function get(string $key): mixed;
    public function set(string $key, mixed $value): void;
    public function setDefault(string $key, mixed $value): void;
    public function replaceValues(array $params): void;
}