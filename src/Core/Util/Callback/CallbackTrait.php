<?php

namespace Blue\Core\Util\Callback;

use Closure;

trait CallbackTrait
{
    private Closure $closure;

    final private function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public static function fromCallable(callable $callable): static
    {
        return new static($callable(...));
    }

    public function __invoke(): string|array|bool
    {
        return $this->resolve();
    }

    public function jsonSerialize(): string|array|bool
    {
        return $this->resolve();
    }
}
