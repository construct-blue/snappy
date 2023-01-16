<?php

declare(strict_types=1);

namespace Blue\Core\Exception;

use Exception;
use Blue\Core\Http\Status;
use Throwable;

use function class_parents;
use function in_array;

class CoreException extends Exception implements Castable
{
    final public function __construct(string $message = "", int|Status $code = 0, ?Throwable $previous = null)
    {
        if ($code instanceof Status) {
            $code = $code->value;
        }
        parent::__construct($message, $code, $previous);
    }

    public static function from(Throwable $throwable): static
    {
        return new static($throwable->getMessage(), $throwable->getCode(), $throwable);
    }

    /**
     * @template T
     * @param class-string<Exception&T> $class
     * @return Exception&T
     */
    public function castTo(string $class): Exception
    {
        if (!in_array(Exception::class, class_parents($class))) {
            throw new InvalidCastError('Cast exception to non exception class: ' . $class);
        }
        return new $class($this->getMessage(), $this->getCode(), $this);
    }
}
