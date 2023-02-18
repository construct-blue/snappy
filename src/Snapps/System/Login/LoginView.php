<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Login;

use Blue\Core\View\Import;
use Blue\Core\View\Component\Link;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\SystemNavigation;

/**
 * @property string $token
 * @property string $backPath
 */
#[Import(__DIR__ . '/Login.ts')]
class LoginView extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'Login',
                'body' => [
                    'header' => [
                        SystemNavigation::new($this->getModel()),
                        'p' => [
                            'svg style="height: 7rem;"' => [
                                '<title>Blue Snappy</title>',
                                '<use href="/logo.svg#logo"/>'
                            ],
                        ],
                    ],
                    Template::include(__DIR__ . '/Login.phtml', [
                        'token' => $this->token
                    ]),
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
