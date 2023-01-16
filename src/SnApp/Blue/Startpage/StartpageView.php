<?php

declare(strict_types=1);

namespace Blue\SnApp\Blue\Startpage;

use Blue\Core\View\PageViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property bool $userIsGuest
 * @property string[] $apps
 */
class StartpageView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Home',
                'body' => [
                    'header' => [
                        'nav' => [
                            ['a href="/"' => 'Home'],
                            fn() => $this->userIsGuest ? [
                                ['a href="/cms/login?redirect=/cms"' => 'CMS'],
                                ['a href="/system/login?redirect=/system"' => 'System'],
                            ] : [
                                ['a href="/cms"' => 'CMS'],
                                ['a href="/system"' => 'System'],
                            ]
                        ],
                        'h1' => 'Home',
                    ],
                    'main' => [
                        'article' => [
                            'h2' => 'Domains',
                            array_map(fn($link) => "<p><a href=\"\\\\$link\"><span>$link</span></a></p>", $this->apps)
                        ]
                    ],
                    'footer' => [

                    ]
                ],
            ],
        ];
    }
}
