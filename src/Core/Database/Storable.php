<?php

declare(strict_types=1);

namespace Blue\Core\Database;

interface Storable
{
    public function toStorage(): array;

    /**
     * @param array $data
     * @return static
     */
    public static function fromStorage(array $data): static;
}
