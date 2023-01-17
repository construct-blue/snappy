<?php

namespace Blue\Snapps\Nicemobil;

use Blue\Core\Analytics\AnalyticsMiddleware;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Util\FaviconHandler;
use Blue\Snapps\Nicemobil\Live\LiveHandler;

class NicemobilSnapp extends AbstractSnapp
{
    protected function getConfigProviderList(): array
    {
        return [
            \Blue\Core\View\ConfigProvider::class,
            ConfigProvider::class
        ];
    }

    protected function initPipeline(): void
    {
        $this->pipe(AnalyticsMiddleware::class);
    }

    protected function initRoutes(): void
    {
        FaviconHandler::addRoutes($this, __DIR__ . '/logo.jpg');
        $this->get('/', LiveHandler::class);
    }
}
