<?php

declare(strict_types=1);

namespace Blue\Snapps\Blue;

use Blue\Core\Analytics\AnalyticsMiddleware;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Authentication\AuthenticationMiddleware;
use Blue\Core\Util\FaviconHandler;
use Blue\Snapps\Blue\Startpage\StartpageHandler;

class BlueSnapp extends AbstractSnapp
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
        $this->pipe(AuthenticationMiddleware::class);
        $this->pipe(AnalyticsMiddleware::class);
    }

    protected function initRoutes(): void
    {
        FaviconHandler::addRoutes($this, __DIR__ . '/logo.png');
        $this->get('/', StartpageHandler::class);
    }
}
