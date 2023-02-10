<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Login;

use Blue\Core\View\Component\Link;
use Blue\Core\View\ClientScript;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\SystemNavigation;

/**
 * @property string $token
 * @property string $backPath
 */
#[ClientScript(__DIR__ . '/Login.ts')]
class LoginView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Login',
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        'p' => [
                            'svg style="height: 7rem;"' => [
                                '<title>Blue Snappy</title>',
                                '<use href="/logo.svg#logo"/>'
                            ],
                        ],
                    ],
                    TemplateViewComponent::forTemplate(__DIR__ . '/Login.phtml'),
                    'footer' => [
                        Link::class => [
                            'href' => $this->backPath,
                            'text' => 'Back',
                            'icon' => 'arrow-left',
                        ],
                    ]
                ],
            ],
        ];
    }
}
