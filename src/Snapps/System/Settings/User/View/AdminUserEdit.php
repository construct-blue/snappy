<?php

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Hidden;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\Settings\User\UserModel;

/**
 * @extends ViewComponent<UserModel>
 */
class AdminUserEdit extends ViewComponent
{
    protected function init()
    {
        parent::init();
        $this->assertModel(UserModel::class);
    }

    public function render(): array
    {
        return [
            Form::new([
                'method' => 'post',
                'action' => $this->currentPath . '/save',
                'content' => [
                    Hidden::new([
                        'name' => 'id',
                        'value' => $this->getModel()->getId(),
                    ]),
                    SubmitButton::new([
                        'icon' => 'save',
                        'text' => 'Save'
                    ]),
                ]
            ]),
        ];
    }
}
