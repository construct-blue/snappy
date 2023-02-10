<?php

declare(strict_types=1);

namespace Blue\Core\Util;

use Blue\Core\Util\Exception\SingletonInheritanceException;

trait SingletonTrait
{
    final private function __construct()
    {
        $this->onConstruct();
    }

    abstract protected function onConstruct(): void;

    public static function instance(): static
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new static();
        }
        $instanceClass = $instance::class;
        if ($instanceClass !== static::class) {
            throw new SingletonInheritanceException('Object was initialized as: ' . $instanceClass);
        }
        return $instance;
    }
}
