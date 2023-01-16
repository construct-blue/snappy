<?php

namespace Blue\SnApp\System\User;

use Blue\Core\Authentication\User;
use Blue\Core\Authentication\UserRole;
use Blue\Core\Authentication\UserState;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

/**
 * @property string $name
 * @property UserRole[] $roles
 * @property UserState $state
 */
class UserSummary extends ViewComponent
{
    public function render(): array
    {
        return [
            $this->name,
            'aside' => [
                array_map(fn(UserRole $role) => ['mark' => $role->value, ' '], $this->roles),
                ' ',
                'mark' => [
                    Icon::class => [
                        'icon' => $this->state->is(UserState::LOCKED) ? 'lock' : 'check'
                    ],
                ],
            ]
        ];
    }
}
