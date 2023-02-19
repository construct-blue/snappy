<?php

declare(strict_types=1);

namespace Blue\Core\View;

interface ViewComponentInterface
{
    public static function new(ViewModelInterface|array $model = [], array $params = []): static;

    public function prepare(string $id, array $params): void;

    public function render(): array;

    public function getId(): string;
}
