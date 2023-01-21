<?php

declare(strict_types=1);

namespace Blue\Snapps\Blue\MyAccount;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\SystemNavigation;
use Blue\Core\Authentication\User;
use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;

/**
 * @property IngressRoute $activeSnapp
 * @property User $user
 */
class MyAccountView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => $this->activeSnapp->getName(),
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
                        'a href="{basePath}/"' => [
                            Icon::class => [
                                'icon' => 'arrow-left'
                            ],
                            ' Back'
                        ]
                    ],
                ],
            ]
        ];
    }
}
