<?php

declare(strict_types=1);

namespace Blue\Core\View;

interface ViewComponentInterface
{
    public function id(): string;

    public function prepare(string $id, array $params): static;

    public function bindChild(ViewComponentInterface $component): static;

    public function render(): array;

    public function action(ViewAction $action): static;

    public function __debugInfo(): array;
}
