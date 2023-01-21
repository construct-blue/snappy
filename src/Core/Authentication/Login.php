<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property string $token
 */
#[Entrypoint(__DIR__ . '/Login.ts')]
class Login extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Login',
                'body' => [
                    'header' => [
                        'h1' => [
                            'svg style="height: 7rem;"' => [
                                '<title>Blue Snappy</title>',
                                '<use href="/logo.svg#logo"/>'
                            ],
                        ],
                    ],
                    TemplateViewComponent::forTemplate(__DIR__ . '/Login.phtml'),
                    'footer' => [
                        'a href="{backPath}"' => [
                            Icon::class => [
                                'icon' => 'arrow-left'
                            ],
                            'span' => 'Back'
                        ],
                    ]
                ]
            ]
        ];
    }
}
