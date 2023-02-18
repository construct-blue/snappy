<?php

declare(strict_types=1);

namespace Blue\Core\View\Component;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

/**
 * @property string $href
 * @property string $text
 * @property array $content
 * @property string $icon
 * @property string $target
 * @property bool $active
 */
class Link extends ViewComponent
{
    public function render(): array
    {
        $attributes = " href=\"$this->href\"";
        if (!empty($this->active)) {
            $attributes .= ' class="active"';
        }
        if (!empty($this->target)) {
            $attributes .= " target=\"$this->target\"";
        }
        return [
            "a$attributes" => fn() => !empty($this->icon) ? [
                Icon::class => [
                    'icon' => $this->icon
                ],
                fn() => empty($this->text) ?: ['span' => $this->text],
                fn() => empty($this->content) ?: $this->content,
            ] : [
                $this->text ?? '',
                fn() => empty($this->content) ?: $this->content,
            ],
        ];
    }
}
