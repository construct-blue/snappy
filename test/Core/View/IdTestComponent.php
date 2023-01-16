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
        $component = new static();
        $component->content = $content;
        return $component;
    }

    public function render(): array
    {
        return [
            "div id=\"{$this->id()}\"" => $this->content
        ];
    }
}
