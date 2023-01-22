<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

/**
 * @property SnappRoute[] $siteSnapps
 * @property string $activePath
 * @property string $basePath
 * @property SnappRoute $snapp
 */
class SnappNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => array_map(fn(SnappRoute $route) => [
                Link::class => [
                    'href' => $this->basePath . '/' . $route->getCode(),
                    'text' => $route->getName(),
                    'active' => ($this->basePath . '/' . $route->getCode()) === $this->activePath,
                ],
            ], $this->siteSnapps),
        ];
    }
}
