<?php

namespace Blue\Snapps\System\User;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

class UserAdd extends ViewComponent
{
    public function render(): array
    {
        return [
            'form is="reactive-form" method="post" action="users/add"' => [
                '<input type="text" name="name" placeholder="Username to add" required/>',
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
