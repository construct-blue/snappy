<?php

declare(strict_types=1);

namespace Blue\Snapps\Settings;

use Blue\Cms\Page\Handler\PageHandler;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Authentication\AuthenticationMiddleware;
use Blue\Core\Authentication\AuthorizationMiddleware;
use Blue\Core\Authentication\UserRole;
use Blue\Core\Http\PostRedirectGetMiddleware;
use Blue\Snapps\Settings\Tesla\TeslaSetupAction;
use Blue\Snapps\Settings\Tesla\TeslaSetupHandler;
use Blue\Snapps\Settings\User\UserAddAction;
use Blue\Snapps\Settings\User\UserDeleteAction;
use Blue\Snapps\Settings\User\UserHandler;
use Blue\Snapps\Settings\User\UserSaveAction;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router;

class SettingsSnapp extends AbstractSnapp
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
        $this->get('/', fn() => new RedirectResponse('/settings/users'));

        $this->get('/users', UserHandler::class);
        $this->post('/users/add', UserAddAction::class);
        $this->post('/users/save', UserSaveAction::class);
        $this->post('/users/delete', UserDeleteAction::class);

        $this->get('/setup/tesla', TeslaSetupHandler::class);
        $this->post('/setup/tesla', TeslaSetupAction::class);

        $this->get('{code:.+}', PageHandler::class);
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
