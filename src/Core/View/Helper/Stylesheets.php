<?php
declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\ClientResources;
use Blue\Core\View\ViewComponent;

/**
 * @property ClientResources $resources
 */
class Stylesheets extends ViewComponent
{
    public function render(): array
    {
        return [
            fn() => array_map(
                fn(string $file) => "<link rel='stylesheet' href='$file'>",
                $this->resources->getCSSFiles()
            )
        ];
    }
}