<?php

declare(strict_types=1);

namespace Blue\Snapps\System\ResetPassword;

use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\ViewComponent;

class ResetPasswordView extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::new([
                'title' => 'Reset Password',
                'body' => [
                    Form::new([
                        'method' => 'post',
                        'content' => [
                            Textfield::new([
                                'name' => 'name',
                                'label' => 'Username',
                            ]),
                            Textfield::new([
                                'name' => 'password',
                                'type' => 'password',
                                'label' => 'New Password',
                            ]),
                            SubmitButton::new([
                                'text' => 'submit'
                            ])
                        ],
                    ]),
                ],
            ])
        ];
    }
}