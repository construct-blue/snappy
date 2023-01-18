<?php

namespace Blue\Core\View\Component\Form;

use Blue\Core\View\ViewComponent;

/**
 * @property string $legend
 * @property array $items
 */
class Fieldset extends ViewComponent
{
    public function render(): array
    {
        return [
            'fieldset' => [
                'legend' => $this->legend,
                $this->items
            ]
        ];
    }
}
