<?php

declare(strict_types=1);

namespace Blue\Snapps\Analytics;

use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Authentication\AuthenticationMiddleware;
use Blue\Core\Authentication\AuthorizationMiddleware;
use Blue\Core\Authentication\UserRole;
use Blue\Core\Http\PostRedirectGetMiddleware;
use Blue\Snapps\Analytics\Day\DayHandler;
use Blue\Snapps\Analytics\Day\DayRefreshAction;
use Blue\Snapps\Analytics\MyAccount\MyAccountHandler;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\Route;

class AnalyticsSnapp extends AbstractSnapp
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
        $this->get('/', fn() => new RedirectResponse('/analytics/day'));

        $this->get('/my-account', MyAccountHandler::class);

        $this->get('/day[/{code}]', DayHandler::class);
        $this->post('/day/refresh', DayRefreshAction::class);
    }

    public function route(string $path, $middleware, ?array $methods = null, ?string $name = null): Route
    {
        $route = parent::route($path, $middleware, $methods, $name);
        $options = $route->getOptions();
        $options[AuthorizationMiddleware::ROUTE_OPTION_ROLES] = [UserRole::ADMIN];
        $route->setOptions($options);
        return $route;
    }
}
