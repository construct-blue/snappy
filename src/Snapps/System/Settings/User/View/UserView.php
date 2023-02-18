<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\ViewComponent;
use Blue\Models\User\User;
use Blue\Snapps\System\Settings\SettingsNavigation;
use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;

/**
 * @property string $currentPath
 * @property array $snappOptions
 * @property User[] $users
 */
class UserView extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'Settings',
                'messages' => $this->messages,
                'validations' => $this->validations,
                'body' => [
                    'header' => [
                        SystemNavigation::new($this->getModel()),
                        SettingsNavigation::new($this->getModel()),
                    ],
                    'main id="main"' => [
                        UserAdd::new($this->getModel()),
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
                                            'currentPath' => $this->currentPath,
                                            'id' => $user->getId(),
                                        ]
                                    ] : [
                                        UserEdit::class => [
                                            'currentPath' => $this->currentPath,
                                            'id' => $user->getId(),
                                            'name' => $user->getName(),
                                            'state' => $user->getState(),
                                            'roles' => $user->getRoles(),
                                            'snapps' => $user->getSnapps(),
                                            'snappOptions' => $this->snappOptions
                                        ]
                                    ]
                                ]
                            ],
                            $this->users
                        )
                    ],
                    SystemFooter::new($this->getModel())
                ],
            ]
        ];
    }
}
