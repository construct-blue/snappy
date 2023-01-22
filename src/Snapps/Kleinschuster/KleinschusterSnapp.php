<?php

namespace Blue\Snapps\Kleinschuster;

use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Http\FaviconHandler;
use Blue\Models\Analytics\AnalyticsMiddleware;
use Blue\Models\Cms\Page\Handler\PageHandler;
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
