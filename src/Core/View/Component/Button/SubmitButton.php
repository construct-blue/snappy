<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Button;

use Blue\Core\View\ViewComponent;

/**
 * @property string $text
 * @property string $formaction
 * @property string $icon
 */
class SubmitButton extends ViewComponent
{
    public function render(): array
    {
        return [
            Button::class => [
                'type' => 'submit',
                'text' => $this->text,
                'formaction' => $this->formaction ?? null,
                'icon' => $this->icon ?? null
            ],
        ];
    }
}
