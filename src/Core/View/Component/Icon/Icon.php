<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Icon;

use Blue\Core\View\{Import, ViewComponent};

/**
 * @property string $icon
 */
#[Import(__DIR__ . '/Icon.ts')]
class Icon extends ViewComponent
{
    public static function include(string $icon): static
    {
        $component = static::new();
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
