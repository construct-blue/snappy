<?php

declare(strict_types=1);

namespace Blue\Core\Util\Callback;

use ArrayAccess;

class CallbackArray implements CallbackInterface, ArrayAccess
{
    use CallbackTrait;

    public function resolve(): array
    {
        return (array)($this->closure)();
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->resolve()[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->resolve()[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // unsupported operation
    }

    public function offsetUnset(mixed $offset): void
    {
        // unsupported operation
    }
}
