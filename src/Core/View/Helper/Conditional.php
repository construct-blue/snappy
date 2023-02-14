<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\ViewComponent;
use Closure;

/**
 * @property string $property
 * @property array|Closure $content
 */
class Conditional extends ViewComponent
{
    private Closure|bool $condition;

    public static function include(Closure|bool $condition, array|Closure $content): static
    {
        $component = static::new();
        $component->condition = $condition;
        $component->content = $content;
        return $component;
    }

    public function render(): array
    {
        if (is_bool($this->condition)) {
            $condition = $this->condition;
        } else {
            $condition = ($this->condition)($this);
        }
        if ($condition) {
            return [$this->content];
        }
        return [];
    }
}