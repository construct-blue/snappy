<?php

declare(strict_types=1);

namespace Blue\Core\View;

interface ViewComponentInterface
{
    public function __id(): string;

    public function __prepare(string $id, array $params): static;

    public function __bindParent(ViewComponentInterface $parent): static;

    public function render(): array;

    public function __action(ViewAction $action): static;

    public function __debugInfo(): array;

    public static function new(array $params = []): static;
}
