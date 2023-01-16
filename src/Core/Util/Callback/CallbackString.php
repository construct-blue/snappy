<?php

declare(strict_types=1);

namespace Blue\Core\Util\Callback;

use Stringable;

class CallbackString implements CallbackInterface, Stringable
{
    use CallbackTrait;

    public function resolve(): string
    {
        return (string) ($this->closure)();
    }

    public function __toString()
    {
        return $this->resolve();
    }
}
