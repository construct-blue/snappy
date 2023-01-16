<?php

declare(strict_types=1);

namespace Blue\Core\View\Exception;

use Blue\Core\Exception\CoreException;
use Blue\Core\View\ViewComponentInterface;

class ViewException extends CoreException
{
    public static function forComponent(string $message, ?ViewComponentInterface $component): static
    {
        if (null !== $component) {
            $message .= ' in ' . ($component->__debugInfo()['name'] ?? get_class($component));
        }
        $exception = new static($message);
        $exception->line = $exception->getTrace()[1]['line'];
        $exception->file = $exception->getTrace()[1]['file'];

        return $exception;
    }
}
