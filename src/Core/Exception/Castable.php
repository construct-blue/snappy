<?php

declare(strict_types=1);

namespace Blue\Core\Exception;

use Throwable;

interface Castable extends Throwable
{
    /**
     * @template T
     * @param class-string<Throwable&T> $class
     * @return Throwable&T
     */
    public function castTo(string $class): Throwable;
}
