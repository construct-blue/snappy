<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Button\ConfirmButton;
use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Checkbox\Checkbox;
use Blue\Core\View\Component\Checkbox\CheckboxGroup;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Hidden;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\ViewComponent;
use Blue\Models\User\UserRole;
use Blue\Models\User\UserState;

/**
 * @property string $currentPath
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
            Form::class => [
                'method' => 'post',
                'action' => $this->currentPath . '/save',
                'content' => [
                    Hidden::class => [
                        'name' => 'id',
                        'value' => $this->id,
                    ],
                    Textfield::new([
                        'label' => 'Username',
                        'name' => 'name',
                        'value' => $this->name,
                    ]),
                    Textfield::new([
                        'label' => 'Password',
                        'name' => 'password',
                        'type' => 'password',
                    ]),
                    CheckboxGroup::new([
                        'name' => 'roles',
                        'legend' => 'Roles',
                        'options' => UserRole::list(),
                        'values' => array_keys(UserRole::list($this->roles)),
                    ]),
                    CheckboxGroup::new([
                        'name' => 'snapps',
                        'legend' => 'Snapps',
                        'options' => $this->snappOptions,
                        'values' => $this->snapps,
                    ]),
                    Hidden::new([
                        'name' => 'state',
                        'value' => UserState::ACTIVE->value,
                    ]),
                    Checkbox::new([
                        'label' => 'Locked',
                        'name' => 'state',
                        'value' => UserState::LOCKED->value,
                        'checked' => $this->state->is(UserState::LOCKED)
                    ]),
                    SubmitButton::class => [
                        'icon' => 'save',
                        'text' => 'Save',
                    ],
                    ConfirmButton::class => [
                        'formaction' => $this->currentPath . '/delete',
                        'text' => 'Delete',
                        'message' => 'Sure?',
                        'type' => 'submit',
                        'icon' => 'trash-2'
                    ],
                ]
            ]
        ];
    }
}
