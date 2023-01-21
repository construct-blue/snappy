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
 * @property bool $fullwidth
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
        if (!empty($this->fullwidth)) {
            $attributes .= ' style="width: 100%"';
        }
        return [
            'button' . $attributes => [
                fn() => !empty($this->icon) ? [
                    Icon::class => [
                        'icon' => $this->icon
                    ],
                    fn() => empty($this->text) ?: ['span' => $this->text],
                ] : [
                    $this->text
                ]
            ],
        ];
    }
}
