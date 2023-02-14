<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\ViewComponent;

/**
 * @property array $content
 */
class Body extends ViewComponent
{
    public static function include(array $content): Body
    {
        $component = static::new();
        $component->content = $content;
        return $component;
    }

    public function render(): array
    {
        return $this->content;
    }
}
