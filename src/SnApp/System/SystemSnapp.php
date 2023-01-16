<?php

declare(strict_types=1);

namespace Blue\SnApp\System;

use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router;
use Blue\SnApp\System\Analytics\AnalyticsHandler;
use Blue\SnApp\System\Analytics\AnalyticsRefreshAction;
use Blue\SnApp\System\Client\Tesla\TeslaSetupAction;
use Blue\SnApp\System\Client\Tesla\TeslaSetupHandler;
use Blue\SnApp\System\User\UserAddAction;
use Blue\SnApp\System\User\UserDeleteAction;
use Blue\SnApp\System\User\UserHandler;
use Blue\SnApp\System\User\UserSaveAction;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Authentication\AuthenticationMiddleware;
use Blue\Core\Authentication\AuthorizationMiddleware;
use Blue\Core\Authentication\UserRole;
use Blue\Core\Http\PostRedirectGetMiddleware;

class SystemSnapp extends AbstractSnapp
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
        $this->pipe(AuthorizationMiddleware::class);
        $this->pipe(PostRedirectGetMiddleware::class);
    }

    protected function initRoutes(): void
    {
        $this->get('/', fn() => new RedirectResponse('/system/users'));
        $this->get('/users', UserHandler::class, 'users');
        $this->post('/users/add', UserAddAction::class);
        $this->post('/users/save', UserSaveAction::class);
        $this->post('/users/delete', UserDeleteAction::class);
        $this->get('/setup/tesla', TeslaSetupHandler::class, 'setup_tesla');
        $this->post('/setup/tesla', TeslaSetupAction::class);
        $this->get('/analytics[/{code}]', AnalyticsHandler::class);
        $this->post('/analytics/refresh', AnalyticsRefreshAction::class);
    }

    public function route(string $path, $middleware, ?array $methods = null, ?string $name = null): Router\Route
    {
        $route = parent::route($path, $middleware, $methods, $name);
        $options = $route->getOptions();
        $options[AuthorizationMiddleware::ROUTE_OPTION_ROLES] = [UserRole::ADMIN];
        $route->setOptions($options);
        return $route;
    }
}
