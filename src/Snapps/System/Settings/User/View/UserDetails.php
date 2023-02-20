<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\User\View;

use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\Settings\User\UserModel;


/**
 * @extends ViewComponent<UserModel>
 * @property string $currentPath
 * @property array $snappOptions
 */
class UserDetails extends ViewComponent
{
    protected function init()
    {
        parent::init();
        $this->assertModel(UserModel::class);
    }

    public function render(): array
    {
        return [
            Details::class => [
                'id' => $this->getModel()->getId(),
                'summary' => [
                    UserSummary::new($this->getModel()),
                ],
                'content' => fn() => $this->getModel()->isAdmin() ? [
                ] : [
                    UserEdit::new($this->getModel(), [
                        'currentPath' => $this->currentPath,
                        'snappOptions' => $this->snappOptions,
                    ]),
                ]
            ]
        ];
    }
}