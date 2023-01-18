<?php

declare(strict_types=1);

namespace Blue\Core\Application\Server;

use Blue\Core\Application\SnappInterface;
use Laminas\Diactoros\ServerRequest;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Application\ApplicationContainerConfig;
use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\Session\SessionMiddleware;
use Blue\Core\Database\Connection;
use Blue\Core\Http\Attribute;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SnappyServer extends AbstractSnapp
{
    /** @var IngressRoute[] */
    private array $snappRoutes = [];

    private function initDbConnection(): void
    {
        if (!$this->getEnv('MYSQL_HOST')) {
            return;
        }
        Connection::main()->initMySQL(
            $this->getEnv('MYSQL_HOST'),
            $this->getEnv('MYSQL_PORT'),
            $this->getEnv('MYSQL_DATABASE'),
            $this->getEnv('MYSQL_USER'),
            $this->getEnv('MYSQL_PASSWORD'),
            $this->getEnv('MYSQL_TABLE_PREFIX', 'blue_'),
        );
    }

    public static function fromEnv(array $env, string $configCacheFile = null): self
    {
        return parent::fromEnv($env, $configCacheFile)->resolve();
    }

    protected function createConfig(): ApplicationContainerConfig
    {
        $config = parent::createConfig();
        $config->addProviderClass(ConfigProvider::class);
        return $config;
    }

    public function addSnapp(SnappInterface $snapp, string $path, string $domain = null): IngressRoute
    {
        $route = new IngressRoute($snapp, $path, $domain);
        if ($domain && $this->getDevDomain()) {
            $route->setDomainSuffix('.' . $this->getDevDomain());
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
                Attribute::SNAPP_ROUTES->setTo($request, $this->snappRoutes)
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
