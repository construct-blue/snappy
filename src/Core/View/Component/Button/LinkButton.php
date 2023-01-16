<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Button;

use Blue\Core\View\ViewComponent;

/**
 * @property string $icon
 * @property string $text
 * @property string $href
 */
class LinkButton extends ViewComponent
{
    public function render(): array
    {
        return [
            'a href={href}' => [
                new Button()
            ]
        ];
    }
}
