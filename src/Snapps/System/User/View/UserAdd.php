<?php

namespace Blue\Snapps\System\User\View;

use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

class UserAdd extends ViewComponent
{
    public function render(): array
    {
        return [
            'form is="reactive-form" method="post" action="users/add"' => [
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
        ];
    }
}
