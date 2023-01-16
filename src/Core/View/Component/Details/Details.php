<?php

namespace Blue\Core\View\Component\Details;

use Blue\Core\View\ViewComponent;

/**
 * @property string $id
 * @property array|string|callable $summary
 * @property array|string|callable $content
 */
class Details extends ViewComponent
{
    public function render(): array
    {
        return [
            'details is="reactive-details"' . 'id="' . $this->id . '"' => [
                'summary' => $this->summary,
                $this->content
            ],
        ];
    }
}
