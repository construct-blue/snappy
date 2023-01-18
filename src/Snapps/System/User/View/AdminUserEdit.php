<?php

namespace Blue\Snapps\System\User\View;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

class AdminUserEdit extends ViewComponent
{
    public function render(): array
    {
        return [
            'form is="reactive-form" method="post" action="users/save"' => [
                '<input type="hidden" name="id" value="{id}"/>',
                '<input type="hidden" name="roles[]" value="admin"/>',
                'label for="password"' => 'Password',
                '<input type="password" id="password" name="password"/>',
                'button type="submit"' => [
                    Icon::class => [
                        'icon' => 'save'
                    ],
                    'span' => 'Save user',
                ]
            ]
        ];
    }
}
