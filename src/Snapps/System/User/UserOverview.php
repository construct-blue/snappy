<?php

declare(strict_types=1);

namespace Blue\Snapps\System\User;

use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;
use Blue\Core\Authentication\User;
use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\PageViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property string[] $messages
 * @property User[] $users
 */
class UserOverview extends ViewComponent
{
    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Users',
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        'h1' => 'Users',
                    ],
                    'main id="main"' => [
                        array_map(fn($message) => ['mark' => $message], $this->messages),
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
                    SystemFooter::class => []
                ],
            ]
        ];
    }
}
