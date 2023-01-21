<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

/**
 * @property IngressRoute $activeSnapp
 * @property IngressRoute[] $systemSnapps
 */
class SystemMenuItems extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                array_map(
                    fn(IngressRoute $route) => [
                        Link::class => [
                            'href' => $route->getUri(),
                            'active' => $route->getCode() === $this->activeSnapp->getCode(),
                            'text' => $route->getName(),
                        ],
                    ],
                    $this->systemSnapps,
                ),
            ],
        ];
    }
}
