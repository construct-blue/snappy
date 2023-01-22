<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Icon;

use Blue\Core\View\{Entrypoint, ViewComponent};

/**
 * @property string $icon
 */
#[Entrypoint(__DIR__ . '/Icon.ts')]
class Icon extends ViewComponent
{
    public static function for(string $icon): static
    {
        $component = new static();
        $component->icon = $icon;
        return $component;
    }

    public function render(): array
    {
        return [
            'icon' => [
                'svg' => "<use href=\"/icons.svg#$this->icon\"/>"
            ]
        ];
    }
}
