<?php

namespace Blue\Snapps\Kleinschuster;

use Blue\Cms\Page\Handler\PageHandler;
use Blue\Core\Analytics\AnalyticsMiddleware;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Util\FaviconHandler;
use Blue\Snapps\Kleinschuster\Home\HomeHandler;

class KleinschusterSnapp extends AbstractSnapp
{
    protected function getConfigProviderList(): array
    {
        return [
            \Blue\Core\View\ConfigProvider::class,
            ConfigProvider::class,
        ];
    }

    protected function initPipeline(): void
    {
        $this->pipe(AnalyticsMiddleware::class);
    }

    protected function initRoutes(): void
    {
        FaviconHandler::addRoutes($this, __DIR__ . '/logo.png');
        $this->get('/', HomeHandler::class);
        $this->get('{code:.+}', PageHandler::class);
    }
}
