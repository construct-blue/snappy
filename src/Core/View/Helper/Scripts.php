<?php
declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\ClientResources;
use Blue\Core\View\ViewComponent;

/**
 * @property ClientResources $resources
 */
class Scripts extends ViewComponent
{
    public function render(): array
    {
        return [
            fn() => array_map(
                fn(string $file) => "<script src='$file'></script>",
                $this->resources->getJSFiles()
            ),
        ];
    }
}