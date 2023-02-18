<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

/**
 * @property SnappRoute[] $siteSnapps
 * @property string $currentPath
 * @property string $cmsBasePath
 * @property SnappRoute $snapp
 */
class SnappNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => array_map(fn(SnappRoute $route) => [
                Link::class => [
                    'href' => $this->cmsBasePath . '/' . $route->getCode(),
                    'text' => $route->getName(),
                    'active' => ($this->cmsBasePath . '/' . $route->getCode()) === $this->currentPath,
                ],
            ], $this->siteSnapps),
        ];
    }
}
