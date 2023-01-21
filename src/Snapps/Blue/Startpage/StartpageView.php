<?php

declare(strict_types=1);

namespace Blue\Snapps\Blue\Startpage;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\SystemNavigation;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;

/**
 * @property IngressRoute $activeSnapp
 * @property bool $userIsGuest
 * @property IngressRoute[] $siteSnapps
 */
class StartpageView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => $this->activeSnapp->getName(),
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
                    'main' => [
                        'h3' => 'Snapps',
                        array_map(
                            fn(IngressRoute $route) => [
                                'p' => [
                                    "a href=\"{$route->getUri()}\"" => $route->getName()
                                ],
                            ],
                            $this->siteSnapps
                        ),
                    ],
                    'footer' => fn() => $this->userIsGuest ?
                        [
                            ['a href="{loginPath}"' => 'Login',],
                        ] : [
                            'div' => [
                                Icon::class => [
                                    'icon' => 'user'
                                ],
                                ' {userName}'
                            ],
                            ['a href="{logoutPath}"' => 'Logout'],
                            'span' => ' | ',
                            ['a href="{basePath}/my-account"' => 'Edit Account'],
                        ],
                ],
            ],
        ];
    }
}
