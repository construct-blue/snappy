<?php

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

class AdminUserEdit extends ViewComponent
{
    public function render(): array
    {
        return [
            'form is="reactive-form" method="post" action="' . $this->currentPath . '/save"' => [
                <<<HTML
<input type="hidden" name="id" value="{$this->id}"/>
HTML,
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
