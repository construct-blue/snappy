<?php

declare(strict_types=1);

namespace Blue\Core\View;

class When extends ViewComponent
{
    private array $content = [];

    public static function empty($value, array $content): static
    {
        $component = new static();
        if (empty($value)) {
            $component->content = $content;
        }
        return $component;
    }

    public static function notEmpty($value, array $content): static
    {
        $component = new static();
        if (!empty($value)) {
            $component->content = $content;
        }
        return $component;
    }

    public function render(): array
    {
        return $this->content;
    }
}
