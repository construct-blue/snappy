<?php

declare(strict_types=1);

namespace Blue\Core\Logger;

use Psr\Log\AbstractLogger;

class PhpErrorLogger extends AbstractLogger
{
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        error_log($message);
    }
}
