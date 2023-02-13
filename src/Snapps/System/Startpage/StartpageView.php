<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Startpage;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\View\Helper\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;

/**
 * @property bool $isLoggedIn
 * @property SnappRoute[] $siteSnapps
 */
class StartpageView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'System',
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        'p' => [
                            'svg style="height: 7rem;"' => [
                                'title' => [
                                    'Blue Snappy',
                                    function () {
                                        if ($this->isLoggedIn) {
                                            echo ' - ';
                                            include '.build';
                                        }
                                        return '';
                                    },
                                ],
                                '<use href="/logo.svg#logo"/>'
                            ],
                        ],
                    ],
                    'main' => [
                        'h3' => 'Snapps',
                        array_map(
                            fn(SnappRoute $route) => [
                                'p' => [
                                    "a href=\"{$route->getUri()}\"" => $route->getName()
                                ],
                            ],
                            $this->siteSnapps
                        ),
                    ],
                    SystemFooter::class => []
                ],
            ],
        ];
    }
}
