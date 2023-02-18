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
 * @property string $type
 */
class Textfield extends ViewComponent
{
    public function render(): array
    {
        $attributes = '';
        if (isset($this->value)) {
            $attributes .= " value=\"$this->value\"";
        }
        if (isset($this->placeholder)) {
            $attributes .= " placeholder=\"$this->placeholder\"";
        }
        if (!empty($this->required)) {
            $attributes .= ' required';
        }
        if (empty($this->type)) {
            $attributes .= ' type="text"';
        } else {
            $attributes .= " type=\"$this->type\"";
        }
        return [
            fn() => isset($this->label) ? [
                "label for=\"{$this->getId()}\"" => $this->label,
                "<input id=\"{$this->getId()}\" name=\"{$this->name}\"$attributes>",
            ] : [
                "<input id=\"{$this->getId()}\" name=\"{$this->name}\"$attributes>",
            ],
        ];
    }
}
