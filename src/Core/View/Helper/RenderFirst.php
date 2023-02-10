<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\ViewComponent;

/**
 * @property array $content
 */
final class RenderFirst extends ViewComponent
{
    public static function from(array $content): RenderFirst
    {
        $component = new RenderFirst();
        $component->content = $content;
        return $component;
    }

    public function render(): array
    {
        return $this->content;
    }
}
