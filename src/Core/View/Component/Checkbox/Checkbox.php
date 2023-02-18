<?php

namespace Blue\Core\View\Component\Checkbox;

use Blue\Core\View\ViewComponent;

/**
 * @property string $label
 * @property string $name
 * @property string $value
 * @property bool $checked
 */
class Checkbox extends ViewComponent
{
    protected function init()
    {
        parent::init();
        $this->getModel()->setDefault('checked', false);
    }

    public function render(): array
    {
        return [
            'label' => [
                <<<HTML
<input type="checkbox" name="{$this->name}" value="{$this->value}"{$this->getChecked()}/>
HTML,
                $this->label
            ]
        ];
    }

    private function getChecked(): string
    {
        if ($this->checked) {
            return ' checked';
        }
        return '';
    }
}
