<?php

declare(strict_types=1);

namespace Blue\Core\Logger;

class LoggerFactory
{
    public function __invoke(): Logger
    {
        return new Logger();
    }
}
