<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Closure;
use Blue\Core\View\Exception\InvalidComponentContentException;
use Blue\Core\View\Exception\MissingPropertyException;

use function is_array;

class ClosureView extends ViewComponent
{
    private Closure $closure;

    public static function from(Closure $closure): static
    {
        $component = new static();
        $component->closure = $closure;
        return $component;
    }

    /**
     * @return array
     * @throws MissingPropertyException|InvalidComponentContentException
     */
    public function render(): array
    {
        if (!isset($this->closure)) {
            throw MissingPropertyException::forComponent('Missing closure', $this);
        }
        $result = ($this->closure)($this);
        if (null === $result) {
            throw InvalidComponentContentException::forComponent('Closure must not return null', $this);
        }
        if (!is_array($result)) {
            $result = [$result];
        }
        return $result;
    }
}
