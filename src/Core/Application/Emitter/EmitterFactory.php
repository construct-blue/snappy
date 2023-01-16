<?php

declare(strict_types=1);

namespace Blue\Core\Application\Emitter;

class EmitterFactory
{
    public function __invoke()
    {
        return new SapiStreamEmitter();
    }
}
