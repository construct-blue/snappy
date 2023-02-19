<?php

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\Settings\User\UserModel;

/**
 * @extends ViewComponent<UserModel>
 */
class UserSummary extends ViewComponent
{
    public function render(): array
    {
        return [
            $this->getModel()->getName(),
            'aside' => [
                array_map(fn(string $name) => ['mark' => $name, ' '], $this->getModel()->getRoleNames()),
                ' ',
                'mark' => [
                    Icon::include($this->getModel()->isLocked() ? 'lock' : 'check'),
                ],
            ]
        ];
    }
}
