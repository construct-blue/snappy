<?php

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;
use Blue\Models\User\UserRole;
use Blue\Models\User\UserState;

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
                array_map(fn(UserRole $role) => ['mark' => $role->getName(), ' '], $this->roles),
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
