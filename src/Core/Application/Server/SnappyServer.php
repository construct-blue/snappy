<?php

declare(strict_types=1);

namespace Blue\Core\Application\Server;

use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Application\ApplicationContainerConfig;
use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\Session\SessionMiddleware;
use Blue\Core\Database\Connection;
use Blue\Core\Http\Attribute;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SnappyServer extends AbstractSnapp
{
    private array $apps = [];

    private function initDbConnection()
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

    public static function fromEnv(array $env, string $configCacheFile = null): static
    {
        $app = parent::fromEnv($env, $configCacheFile);
        $app->initDbConnection();
        return $app;
    }

    protected function createConfig(): ApplicationContainerConfig
    {
        $config = parent::createConfig();
        $config->addProviderClass(ConfigProvider::class);
        return $config;
    }

    public function app(AbstractSnapp $application, string $path, string $domain = null): self
    {
        if ($this->getDevDomain() && $domain) {
            $domain = $domain . '.' . $this->getDevDomain();
        }
        $this->apps[] = IngressRoute::app($application, $path, $domain);
        return $this;
    }

    protected function initPipeline(): void
    {
        $this->pipe(
            fn(ServerRequestInterface $request, RequestHandlerInterface $handler) => $handler->handle(
                Attribute::APPS->setTo($request, $this->apps)
            )
        );
        foreach ($this->apps as $app) {
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
        $this->init();
        parent::run();
    }
}
