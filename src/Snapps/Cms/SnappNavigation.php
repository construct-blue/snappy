<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms;

use Blue\Core\View\ViewComponent;

/**
 * @property array $snapps
 * @property string $snapp
 */
class SnappNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => array_map(fn($href, $name) => [
                "a href=\"{basePath}/$href\""
                . ($href === ($this->snapp ?? '') ? ' class="active"' : '') => $name,
            ], array_keys($this->snapps), array_values($this->snapps)),
        ];
    }
}
