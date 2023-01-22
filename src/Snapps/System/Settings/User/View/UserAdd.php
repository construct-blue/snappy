<?php

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

class UserAdd extends ViewComponent
{
    public function render(): array
    {
        return [
            'form is="reactive-form" method="post" action="{activePath}/add"' => [
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
