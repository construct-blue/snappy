<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms;

use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Authentication\AuthenticationMiddleware;
use Blue\Core\Authentication\AuthorizationMiddleware;
use Blue\Core\Authentication\UserRole;
use Blue\Core\Http\PostRedirectGetMiddleware;
use Blue\Snapps\Cms\Block\BlockAddAction;
use Blue\Snapps\Cms\Block\BlockDeleteAction;
use Blue\Snapps\Cms\Block\BlockHandler;
use Blue\Snapps\Cms\Block\BlockSaveAction;
use Blue\Snapps\Cms\Page\PageHandler;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\Route;

class CmsSnapp extends AbstractSnapp
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
        $this->pipe(AuthenticationMiddleware::class);
        $this->pipe(AuthorizationMiddleware::class);
        $this->pipe(PostRedirectGetMiddleware::class);
    }

    protected function initRoutes(): void
    {
        $this->get('/', fn() => new RedirectResponse('/cms/blocks'));
        $this->get('/blocks[/[{snapp}]]', BlockHandler::class);
        $this->post('/blocks/delete[/[{snapp}]]', BlockDeleteAction::class);
        $this->post('/blocks/add[/[{snapp}]]', BlockAddAction::class);
        $this->post('/blocks/save[/[{snapp}]]', BlockSaveAction::class);

        $this->get('/pages', PageHandler::class);
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
