<?php

namespace Blue\Core\View\Component\Radio;

use Blue\Core\View\Component\Fieldset\Fieldset;
use Blue\Core\View\ViewComponent;

/**
 * @property string $legend
 * @property string $name
 * @property string[] $options
 * @property string $value
 */
class RadioGroup extends ViewComponent
{
    public function render(): array
    {
        return [
            Fieldset::class => [
                'legend' => $this->legend,
                'items' => array_map(
                    fn($key, $value) => [
                        Radio::class => [
                            'label' => $value,
                            'value' => $key,
                            'name' => $this->name,
                            'checked' => $key === $this->value
                        ]
                    ],
                    array_keys($this->options),
                    array_values($this->options)
                )
            ],
        ];
    }
}
