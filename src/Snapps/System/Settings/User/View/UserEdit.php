<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Checkbox\CheckboxGroup;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Component\Radio\RadioGroup;
use Blue\Core\View\ViewComponent;
use Blue\Models\User\UserRole;
use Blue\Models\User\UserState;

/**
 * @property string $id
 * @property string $name
 * @property UserState $state
 * @property UserRole[] $roles
 * @property array $snapps
 * @property array $snappOptions
 */
class UserEdit extends ViewComponent
{
    public function render(): array
    {
        return [
            'form is="reactive-form" method="post" action="{activePath}/save"' => [
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
                [
                    CheckboxGroup::class => [
                        'name' => 'snapps',
                        'legend' => 'Snapps',
                        'options' => $this->snappOptions,
                        'values' => $this->snapps,
                    ],
                ],
                'button type="submit"' => [
                    Icon::class => [
                        'icon' => 'save'
                    ],
                    'span' => 'Save',
                ],
                'button is="confirm-button" message="Sure?" type="submit" formaction="{activePath}/delete"' => [
                    Icon::class => [
                        'icon' => 'trash-2'
                    ],
                    'span' => 'Delete',
                ],
            ]
        ];
    }
}
