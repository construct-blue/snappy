<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\Exception\InvalidComponentContentException;
use Blue\Core\View\Exception\MissingPropertyException;
use Blue\Core\View\ViewComponent;
use Closure;

use function is_array;

class Functional extends ViewComponent
{
    private Closure $closure;

    public static function include(Closure $closure): static
    {
        $component = static::new();
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
        if (is_bool($result)) {
            return [];
        }
        if (!is_array($result)) {
            $result = [$result];
        }
        return $result;
    }
}
