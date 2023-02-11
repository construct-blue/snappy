<?php

namespace Blue\Core\View\Helper;

use Blue\Core\View\ViewComponent;

/**
 * @property string $tag
 * @property array $content
 */
class IdWrapper extends ViewComponent
{
    public function render(): array
    {
        return [
            $this->tag . ' id="' . $this->__id() . '"' => $this->content
        ];
    }
}
