<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Button;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

/**
 * @property string $text
 * @property string $message
 * @property string $type
 * @property string $formaction
 * @property string $icon
 */
class ConfirmButton extends ViewComponent
{
    public function render(): array
    {
        $attributes = '';
        if (isset($this->formaction)) {
            $attributes .= " formaction=\"{$this->formaction}\"";
        }
        if (isset($this->type)) {
            $attributes .= " type=\"{$this->type}\"";
        }
        return [
            'button is="confirm-button" message="{message}"' . $attributes => [
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
