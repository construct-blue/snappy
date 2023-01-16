<?php

namespace Blue\SnApp\Kleinschuster;

use Blue\SnApp\Kleinschuster\ConfigProvider;
use Blue\SnApp\Kleinschuster\Home\HomeHandler;
use Blue\Core\Analytics\AnalyticsMiddleware;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Util\FaviconHandler;

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
    }
}
