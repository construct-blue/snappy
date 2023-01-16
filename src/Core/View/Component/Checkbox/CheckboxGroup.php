<?php

namespace Blue\Core\View\Component\Checkbox;

use Blue\Core\View\Component\Fieldset\Fieldset;
use Blue\Core\View\ViewComponent;

/**
 * @property string $legend
 * @property string $name
 * @property string[] $options
 * @property string[] $values
 */
class CheckboxGroup extends ViewComponent
{
    public function render(): array
    {
        return [
            Fieldset::class => [
                'legend' => $this->legend,
                'items' => array_map(
                    fn($key, $value) => [
                        Checkbox::class => [
                            'label' => $value,
                            'value' => $key,
                            'name' => $this->name . '[]',
                            'checked' => in_array($key, $this->values)
                        ]
                    ],
                    array_keys($this->options),
                    array_values($this->options)
                )
            ],
        ];
    }
}
