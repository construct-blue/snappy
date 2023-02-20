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
use Blue\Snapps\System\Settings\User\UserModel;

/**
 * @extends ViewComponent<UserModel>
 * @property string $currentPath
 * @property array $snappOptions
 */
class UserEdit extends ViewComponent
{
    protected function init()
    {
        parent::init();
        $this->assertModel(UserModel::class);
    }

    public function render(): array
    {
        return [
            Form::class => [
                'method' => 'post',
                'action' => $this->currentPath . '/save',
                'content' => [
                    Hidden::class => [
                        'name' => 'id',
                        'value' => $this->getModel()->getId(),
                    ],
                    Textfield::new([
                        'label' => 'Username',
                        'name' => 'name',
                        'value' => $this->getModel()->getName(),
                    ]),
                    CheckboxGroup::new([
                        'name' => 'roles',
                        'legend' => 'Roles',
                        'options' => UserRole::list(),
                        'values' => $this->getModel()->getRoles(),
                    ]),
                    CheckboxGroup::new([
                        'name' => 'snapps',
                        'legend' => 'Snapps',
                        'options' => $this->snappOptions,
                        'values' => $this->getModel()->getSnapps(),
                    ]),
                    Hidden::new([
                        'name' => 'locked',
                        'value' => '0',
                    ]),
                    Checkbox::new([
                        'label' => 'Locked',
                        'name' => 'locked',
                        'value' => '1',
                        'checked' => $this->getModel()->isLocked()
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
                    ConfirmButton::new([
                        'formaction' => $this->currentPath . '/reset-password',
                        'text' => 'Reset Password',
                        'message' => 'Sure?',
                        'type' => 'submit',
                    ]),
                ]
            ]
        ];
    }
}
