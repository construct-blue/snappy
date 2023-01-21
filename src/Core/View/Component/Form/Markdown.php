<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Form;

use Blue\Core\View\ViewComponent;

/**
 * @property bool $required
 * @property string|null $label
 * @property string $name
 * @property string $value
 * @property string $placeholder
 * @property string $error
 * @property int $rows
 */
class Markdown extends ViewComponent
{
    public function render(): array
    {
        $attributes = ' style="font-family: Markdown; resize: vertical;"';
        if (isset($this->placeholder)) {
            $attributes .= " placeholder=\"$this->placeholder\"";
        }
        if (!empty($this->required)) {
            $attributes .= ' required';
        }
        $rows = $this->rows ?? 5;
        if (!empty($this->value)) {
            $attributes .= ' rows="' . (mb_substr_count($this->value, "\n") + $rows) . '"';
        } else {
            $attributes .= ' rows="' . $rows . '"';
        }
        return [
            fn() => isset($this->label) ? [
                "label for=\"{$this->__id()}\"" => $this->label,
                "textarea id=\"{$this->__id()}\" type=\"text\" name=\"{$this->name}\"$attributes" => $this->value ?? '',
            ] : [
                "textarea id=\"{$this->__id()}\" type=\"text\" name=\"{$this->name}\"$attributes" => $this->value ?? '',
            ],
        ];
    }
}
