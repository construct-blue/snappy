<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;

class ViewLogger extends AbstractLogger
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
