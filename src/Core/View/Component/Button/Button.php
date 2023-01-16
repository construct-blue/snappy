<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Button;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

/**
 * @property string $text
 * @property string $icon
 */
class Button extends ViewComponent
{
    public function render(): array
    {
        return [
            'button' => [
                fn() => isset($this->icon) ? [
                    Icon::class => [
                        'icon' => $this->icon
                    ],
                    'span' => $this->text
                ] : [
                    $this->text
                ]
            ],
        ];
    }
}
