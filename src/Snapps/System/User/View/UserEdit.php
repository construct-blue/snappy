<?php

declare(strict_types=1);

namespace Blue\Snapps\System\User\View;

use Blue\Core\Authentication\UserRole;
use Blue\Core\Authentication\UserState;
use Blue\Core\View\Component\Checkbox\CheckboxGroup;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Component\Radio\RadioGroup;
use Blue\Core\View\ViewComponent;

/**
 * @property string $id
 * @property string $name
 * @property UserState $state
 * @property UserRole[] $roles
 */
class UserEdit extends ViewComponent
{
    public function render(): array
    {
        return [
            'form is="reactive-form" method="post" action="users/save"' => [
                '<input type="hidden" name="id" value="{id}"/>',
                'label for="name_{id}"' => 'Username',
                '<input type="text" id="name_{id}" name="name" value="{name}"/>',
                'label for="password_{id}"' => 'Password',
                '<input type="password" id="password_{id}" name="password"/>',
                RadioGroup::class => [
                    'name' => 'state',
                    'legend' => 'Status',
                    'options' => UserState::list(),
                    'value' => $this->state->value,
                ],
                CheckboxGroup::class => [
                    'name' => 'roles',
                    'legend' => 'Roles',
                    'options' => UserRole::list(),
                    'values' => array_keys(UserRole::list($this->roles)),
                ],
                'button type="submit"' => [
                    Icon::class => [
                        'icon' => 'save'
                    ],
                    'span' => 'Save',
                ],
                'button is="confirm-button" message="Sure?" type="submit" formaction="users/delete"' => [
                    Icon::class => [
                        'icon' => 'trash-2'
                    ],
                    'span' => 'Delete',
                ],
            ]
        ];
    }
}
