<?php

declare(strict_types=1);

namespace Blue\Core\Util;

trait MultitonTrait
{
    final private function __construct(private readonly int $instanceId)
    {
        $this->onConstruct();
    }

    abstract protected function onConstruct(): void;

    public static function instance(int $id): static
    {
        static $storage = [];

        if (!isset($storage[$id])) {
            $storage[$id] = new static($id);
        }
        return $storage[$id];
    }

    public function getInstanceId(): int
    {
        return $this->instanceId;
    }
}
