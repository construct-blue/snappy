<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Helper\Document;
use Blue\Core\View\ViewComponent;
use Blue\Models\User\User;
use Blue\Snapps\System\Settings\SettingsNavigation;
use Blue\Snapps\System\Settings\User\UserModel;
use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;

/**
 * @property array $messages
 * @property array $validations
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
                                UserDetails::new(
                                    UserModel::initFromUser($user),
                                    [
                                        'currentPath' => $this->currentPath,
                                        'snappOptions' => $this->snappOptions
                                    ]
                                )
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
