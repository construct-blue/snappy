<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Form;

use Blue\Core\View\ViewComponent;

/**
 * @property string $name
 * @property string $value
 */
class Hidden extends ViewComponent
{
    public function render(): array
    {
        return [
            <<<HTML
<input type="hidden" name="{$this->name}" value="{$this->value}"/>
HTML
        ];
    }
}
