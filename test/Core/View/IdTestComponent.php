<?php

namespace BlueTest\Core\View;

use Blue\Core\View\ViewComponent;

/**
 * @property array $content
 */
class IdTestComponent extends ViewComponent
{
    public static function for(array $content): static
    {
        $component = static::new();
        $component->content = $content;
        return $component;
    }

    public function render(): array
    {
        return [
            "div id=\"{$this->__id()}\"" => $this->content
        ];
    }
}
