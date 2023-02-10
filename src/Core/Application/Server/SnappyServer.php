<?php

declare(strict_types=1);

namespace Blue\Core\Application\Server;

use Blue\Core\Application\SnappInterface;
use Blue\Core\Environment\Environment;
use Laminas\Diactoros\ServerRequest;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Application\ApplicationContainerConfig;
use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\Application\Session\SessionMiddleware;
use Blue\Core\Database\Connection;
use Blue\Core\Http\RequestAttribute;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SnappyServer extends AbstractSnapp
{
    /** @var SnappRoute[] */
    private array $snappRoutes = [];

    private function initDbConnection(): void
    {
        Connection::main()->initMySQL();
    }

    public static function default(): self
    {
        return parent::default()->resolve();
    }

    protected function createConfig(): ApplicationContainerConfig
    {
        $config = parent::createConfig();
        $config->addProviderClass(ConfigProvider::class);
        return $config;
    }

    public function addSnapp(SnappInterface $snapp, string $path, string $domain = null): SnappRoute
    {
        $route = new SnappRoute($snapp, $path, $domain);
        $env = Environment::instance();
        if ($domain && $env->getDevDomain()) {
            $route->setDomainSuffix('.' . $env->getDevDomain());
        }
        return $this->snappRoutes[] = $route;
    }

    public function build(): void
    {
        /** @var RouterInterface $router */
        $router = $this->getContainer()->get(RouterInterface::class);
        $router->match(new ServerRequest());
        foreach ($this->snappRoutes as $app) {
            $app->build();
        }
    }

    protected function initPipeline(): void
    {
        $this->pipe(
            fn(ServerRequestInterface $request, RequestHandlerInterface $handler) => $handler->handle(
                RequestAttribute::SNAPP_ROUTES->setTo($request, $this->snappRoutes)
            )
        );
        foreach ($this->snappRoutes as $app) {
            $this->pipe($app);
        }
        $this->pipe(ImplicitHeadMiddleware::class);
        $this->pipe(ImplicitOptionsMiddleware::class);
        $this->pipe(MethodNotAllowedMiddleware::class);
        $this->pipe(SessionMiddleware::class);
    }

    protected function initRoutes(): void
    {
    }

    public function run(): void
    {
        $this->initDbConnection();
        $this->init();
        parent::run();
    }
}
