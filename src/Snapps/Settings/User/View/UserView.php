<?php

declare(strict_types=1);

namespace Blue\Snapps\Settings\User\View;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\SystemNavigation;
use Blue\Core\Authentication\User;
use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Settings\SettingsNavigation;

/**
 * @property IngressRoute $activeSnapp
 * @property User[] $users
 */
class UserView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => $this->activeSnapp->getName(),
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        SettingsNavigation::class => [],
                    ],
                    'main id="main"' => [
                        new UserAdd(),
                        array_map(
                            fn(User $user) => [
                                Details::class => [
                                    'id' => $user->getId(),
                                    'summary' => [
                                        UserSummary::class => [
                                            'name' => $user->getName() ?? $user->getId(),
                                            'roles' => $user->getRoles(),
                                            'state' => $user->getState(),
                                        ],
                                    ],
                                    'content' => fn() => $user->isAdmin() ? [
                                        AdminUserEdit::class => [
                                            'id' => $user->getId(),
                                        ]
                                    ] : [
                                        UserEdit::class => [
                                            'id' => $user->getId(),
                                            'name' => $user->getName(),
                                            'state' => $user->getState(),
                                            'roles' => $user->getRoles(),
                                        ]
                                    ]
                                ]
                            ],
                            $this->users
                        )
                    ],
                ],
            ]
        ];
    }
}
