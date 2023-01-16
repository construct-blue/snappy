<?php

declare(strict_types=1);

namespace Blue\Core\Util\Callback;

class CallbackBool implements CallbackInterface
{
    use CallbackTrait;

    public function resolve(): bool
    {
        return (bool) ($this->closure)();
    }
}
