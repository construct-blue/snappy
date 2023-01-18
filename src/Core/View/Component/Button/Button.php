<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Button;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

/**
 * @property string $text
 * @property string $type
 * @property string $formaction
 * @property string $icon
 */
class Button extends ViewComponent
{
    public function render(): array
    {
        $attributes = '';
        if (!empty($this->formaction)) {
            $attributes .= " formaction=\"{$this->formaction}\"";
        }
        if (!empty($this->type)) {
            $attributes .= " type=\"{$this->type}\"";
        }
        return [
            'button' . $attributes => [
                fn() => !empty($this->icon) ? [
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
