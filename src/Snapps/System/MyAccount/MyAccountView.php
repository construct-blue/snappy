<?php

declare(strict_types=1);

namespace Blue\Snapps\System\MyAccount;

use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\Component\Link;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\ViewComponent;
use Blue\Models\User\User;
use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;

/**
 * @property User $user
 * @property string $backUrl
 */
class MyAccountView extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'My Account',
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                    ],
                    'main' => [
                        Form::class => [
                            'method' => 'post',
                            'id' => 'form',
                            'content' => [
                                fn() => $this->user->isAdmin() ?: [
                                    'p' => [
                                        Textfield::class => [
                                            'name' => 'name',
                                            'label' => 'Username',
                                            'value' => $this->user->getName()
                                        ]
                                    ]
                                ],
                                [
                                    'p' => [
                                        Textfield::class => [
                                            'name' => 'password',
                                            'label' => 'Password',
                                            'type' => 'password'
                                        ]
                                    ]
                                ],
                                [
                                    'p' => [
                                        SubmitButton::class => [
                                            'text' => 'Save',
                                        ]
                                    ]
                                ],
                            ],
                        ],
                    ],
                    'footer' => [
                        'p' => [
                            Link::class => [
                                'icon' => 'arrow-left',
                                'text' => 'Back',
                                'href' => $this->backUrl,
                            ],
                        ],
                        SystemFooter::new(),
                    ],
                ],
            ]
        ];
    }
}
