<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Button;

use Blue\Core\View\ViewComponent;

/**
 * @property string $icon
 * @property string $text
 * @property string $href
 * @property bool $fullwidth
 * @property null|string $target
 */
class LinkButton extends ViewComponent
{
    public function render(): array
    {
        $attributes = '';
        if (!empty($this->target)) {
            $attributes .= ' target="' . $this->target . '"';
        }
        return [
            'a href={href}' . $attributes => [
                new Button()
            ]
        ];
    }
}
