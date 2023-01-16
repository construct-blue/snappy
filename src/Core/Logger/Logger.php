<?php

declare(strict_types=1);

namespace Blue\Core\Logger;

use Blue\Core\Util\Placeholder\PlaceholderHelper;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;
use Throwable;

use function date;
use function strtoupper;

class Logger extends AbstractLogger
{
    public static LoggerInterface $logger;
    public const CONTEXT_EXCEPTION = 'exception';

    public function __construct()
    {
        if (!isset(self::$logger)) {
            self::$logger = new PhpErrorLogger();
        }
    }

    public function log($level, Stringable|string $message, array $context = []): void
    {
        $exception = null;
        if ($message instanceof Throwable) {
            $context[self::CONTEXT_EXCEPTION] = $message;
            $message = '';
        }

        if (isset($context[self::CONTEXT_EXCEPTION]) && $context[self::CONTEXT_EXCEPTION] instanceof Throwable) {
            $exception = $context[self::CONTEXT_EXCEPTION];
        }

        $context['timestamp'] = date(DATE_ATOM);
        $context['level'] = strtoupper($level);

        if ($exception) {
            $context['message'] = $exception->getMessage();
            $context['trace'] = $exception->getTraceAsString();
            $context['code'] = $exception->getCode();
            $context['file'] = $exception->getFile();
            $context['line'] = $exception->getLine();
            $message = "{message} in {file}:{line}, $message";
        }

        $message = PlaceholderHelper::replacePlaceholder("[{timestamp}] ({level}): $message", $context);

        self::$logger->log($level, $message, $context);
    }
}
