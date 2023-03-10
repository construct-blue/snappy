<?php

declare(strict_types=1);

namespace Blue\Core\View\Exception;

use Blue\Core\Exception\CoreException;
use Blue\Core\View\ViewComponentInterface;
use Exception;
use ReflectionObject;

class ViewException extends CoreException
{
    public static function forComponent(string $message, ?ViewComponentInterface $component): static
    {
        if (null !== $component) {
            $message .= ' in ' . ($component->__debugInfo()['name'] ?? get_class($component));
        }

        $exception = new static($message);

        if (in_array(ViewComponentInterface::class, class_implements($exception->getTrace()[1]['class']))) {
            $exception->line = $exception->getTrace()[1]['line'];
            $exception->file = $exception->getTrace()[1]['file'];
        } elseif (null !== $component) {
            $reflection = new ReflectionObject($component);
            try {
                $exception->line = $reflection->getMethod('render')->getStartLine();
            } catch (Exception) {
                $exception->line = $reflection->getStartLine();
            }
            $exception->file = $reflection->getFileName();
        }

        return $exception;
    }
}
