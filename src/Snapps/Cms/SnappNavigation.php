<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

/**
 * @property IngressRoute[] $siteSnapps
 * @property IngressRoute $snapp
 */
class SnappNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => array_map(fn(IngressRoute $route) => [
                Link::class => [
                    'href' => "{basePath}/{$route->getCode()}",
                    'text' => $route->getName(),
                    'active' => $route->getCode() === $this->snapp->getCode(),
                ],
            ], $this->siteSnapps),
        ];
    }
}
