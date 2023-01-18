<?php

namespace Blue\Core\View;

/**
 * @property string $tag
 * @property array $content
 */
class IdViewComponent extends ViewComponent
{
    public function render(): array
    {
        return [
            $this->tag . ' id="' . $this->__id() . '"' => $this->content
        ];
    }
}
