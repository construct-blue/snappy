<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\ClientResources;
use Blue\Core\View\ViewComponent;

/**
 * @property array $files
 */
class Stylesheets extends ViewComponent
{
    public static function include(array $files): static
    {
        $component = static::new();
        $component->files = $files;
        return $component;
    }


    public function render(): array
    {
        return [
            fn() => array_map(
                fn(string $file) => "<link rel='stylesheet' href='$file'>",
                $this->files
            )
        ];
    }
}
