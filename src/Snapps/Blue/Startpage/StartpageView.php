<?php

declare(strict_types=1);

namespace Blue\Snapps\Blue\Startpage;

use Blue\Core\View\PageWrapper;
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
            PageWrapper::class => [
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
                        'h1' => [
                            'svg height="7rem"' => [
                                '<title>Blue Snappy</title>',
                                '<use href="/logo.svg#logo"/>'
                            ],
                        ],
                    ],
                    'main' => [
                        'div' => [
                            'h3' => 'Snapps',
                            array_map(
                                fn($link, $name) => "<p><a href=\"$link\"><span>$name</span></a></p>",
                                array_keys($this->apps),
                                array_values($this->apps)
                            )
                        ]
                    ],
                    'footer' => [

                    ]
                ],
            ],
        ];
    }
}
