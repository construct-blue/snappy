<?php

declare(strict_types=1);

namespace Blue\Core\Application\Error;

use Laminas\Stratigility\Middleware\ErrorHandler;
use Blue\Core\Logger\Logger;
use Psr\Container\ContainerInterface;
use Throwable;

class ErrorLoggerDelegator
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): ErrorHandler
    {
        /** @var ErrorHandler $errorHandler */
        $errorHandler = $callback();
        $logger = new Logger();
        $errorHandler->attachListener(fn(Throwable $throwable) => $logger->error($throwable));
        return $errorHandler;
    }
}
