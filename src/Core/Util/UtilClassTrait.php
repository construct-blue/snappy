<?php

declare(strict_types=1);

namespace Blue\Core\Util;

use Blue\Core\Util\Exception\StaticClassException;

/**
 * @internal
 */
trait UtilClassTrait
{
    final private function __construct()
    {
        throw new StaticClassException('Util class must not be instantiated.');
    }
}
