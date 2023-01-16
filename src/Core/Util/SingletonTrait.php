<?php

declare(strict_types=1);

namespace Blue\Core\Util;

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
        return $instance;
    }
}
