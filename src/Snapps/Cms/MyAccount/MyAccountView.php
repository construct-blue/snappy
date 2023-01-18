<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\MyAccount;

use Blue\Core\View\Component\Button\LinkButton;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Cms\CmsNavigation;

class MyAccountView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'My Account - CMS',
                'body' => [
                    'header' => [
                        new CmsNavigation(),
                        'h1' => 'My Account'
                    ],
                    'main' => [
                        LinkButton::class => [
                            'text' => 'Logout',
                            'href' => '{logoutPath}',
                            'icon' => 'log-out',
                        ],
                    ],
                ],
            ]
        ];
    }
}
