<?php

declare(strict_types=1);

namespace Blue\Snapps\Analytics\MyAccount;

use Blue\Core\View\Component\Button\LinkButton;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Analytics\AnalyticsNavigation;

class MyAccountView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'My Account',
                'body' => [
                    'header' => [
                        new AnalyticsNavigation(),
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
