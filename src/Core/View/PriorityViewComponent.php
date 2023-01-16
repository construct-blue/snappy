<?php

declare(strict_types=1);

namespace Blue\Core\View;

/**
 * @property array $content
 */
final class PriorityViewComponent extends ViewComponent
{
    public static function from(array $vca): PriorityViewComponent
    {
        $component = new PriorityViewComponent();
        $component->content = $vca;
        return $component;
    }

    public function render(): array
    {
        return $this->content;
    }
}
