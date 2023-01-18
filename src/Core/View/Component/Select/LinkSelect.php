<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Select;

use Blue\Core\View\ViewComponent;

/**
 * @property string $selected
 * @property array $options
 * @property callable $hrefCallback
 */
class LinkSelect extends ViewComponent
{
    public function render(): array
    {
        return [
            'select onchange="window.location = this.value"' => [
                array_map($this->buildOption(...), array_keys($this->options), array_values($this->options)),
            ],
        ];
    }

    private function buildOption(string $value, string $name): array
    {
        if (($this->selected ?? '') === $value) {
            return ["option value=\"{$this->href($value)}\" selected" => $name];
        }
        return ["option value=\"{$this->href($value)}\"" => $name];
    }

    private function href(string $value): string
    {
        if (isset($this->hrefCallback)) {
            return ($this->hrefCallback)($value);
        }
        return $value;
    }
}
