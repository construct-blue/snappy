<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Form;

use Blue\Core\View\ViewComponent;

/**
 * @property string $method
 * @property string $action
 * @property string $id
 * @property array $content
 */
class Form extends ViewComponent
{
    public function render(): array
    {
        return [
            'form is="reactive-form"' . $this->attributes() => $this->content
        ];
    }

    private function attributes(): string
    {
        $attributes = '';
        if (!empty($this->id)) {
            $attributes .= " id=\"$this->id\"";
        }
        if (isset($this->method)) {
            $attributes .= " method=\"$this->method\"";
        }
        if (isset($this->action)) {
            $attributes .= " action=\"$this->action\"";
        }
        return $attributes;
    }
}
