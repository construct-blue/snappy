<?php

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

/**
 * @property string $currentPath
 */
class UserAdd extends ViewComponent
{
    public function render(): array
    {
        return [
            Form::class => [
                'method' => 'post',
                'action' => $this->currentPath . '/add',
                'content' =>  [
                    Textfield::class => [
                        'name' => 'name',
                        'placeholder' => 'Username to add',
                        'required' => true,
                    ],
                    'button type="submit"' => [
                        Icon::class => [
                            'icon' => 'plus'
                        ],
                        'span' => 'Add user'
                    ]
                ],
            ],
        ];
    }
}
