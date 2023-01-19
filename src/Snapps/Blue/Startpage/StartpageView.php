<?php

declare(strict_types=1);

namespace Blue\Snapps\Blue\Startpage;

use Blue\Core\View\Component\Button\LinkButton;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;

/**
 * @property bool $userIsGuest
 * @property string[] $snapps
 * @property string[] $managers
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
                                array_keys($this->snapps),
                                array_values($this->snapps)
                            ),
                        ],

                    ],
                    'footer' => [
                         [
                            array_map(
                                fn($link, $name) => [
                                    LinkButton::fromParams([
                                        'text' => $name,
                                        'href' => $this->userIsGuest ? "$link/login?redirect=$link" : $link,
                                    ]),
                                    ' '
                                ],
                                array_keys($this->managers),
                                array_values($this->managers)
                            )
                         ],
                    ]
                ],
            ],
        ];
    }
}
