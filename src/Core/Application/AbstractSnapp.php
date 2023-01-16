<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\Application;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Mezzio\Router\RouteCollectorInterface;
use Blue\Core\Application\Debug\ConfigProvider as DebugConfigProvider;
use Blue\Core\View\DefaultVariableMiddleware;

abstract class AbstractSnapp extends Application
{
    public const ENV_DEV_DOMAIN = 'DEV_DOMAIN';
    public const ENV_DEV_MODE = 'DEV_MODE';
    public const ENV_CONFIG_CACHE_FILE = 'CONFIG_CACHE_FILE';

    private ApplicationContainer $container;
    private array $env;

    final private function __construct(array $env = [])
    {
        $this->env = $env;
        parent::__construct(
            $this->getContainer()->get(MiddlewareFactory::class),
            $this->getContainer()->get(MiddlewarePipeInterface::class),
            $this->getContainer()->get(RouteCollectorInterface::class),
            $this->getContainer()->get(RequestHandlerRunnerInterface::class)
        );
    }

    public function newInstance(): static
    {
        return new static($this->env);
    }

    public static function fromEnv(array $env, string $configCacheFile = null): static
    {
        $env[self::ENV_CONFIG_CACHE_FILE] = $configCacheFile;
        return new static($env);
    }

    protected function getEnv(string $name, $default = null)
    {
        return $this->env[$name] ?? $default;
    }

    protected function getDevDomain(): ?string
    {
        return $this->getEnv(self::ENV_DEV_DOMAIN);
    }

    protected function isDevMode(): bool
    {
        return (bool)$this->getEnv(self::ENV_DEV_MODE);
    }

    protected function getConfigCacheFile(): ?string
    {
        return $this->getEnv(self::ENV_CONFIG_CACHE_FILE);
    }

    final public function init(): static
    {
        $this->initRoutes();
        $this->pipe(ErrorHandler::class);
        $this->pipe(RouteMiddleware::class);
        $this->pipe(DefaultVariableMiddleware::class);
        $this->initPipeline();
        $this->pipe(DefaultVariableMiddleware::class);
        $this->pipe(DispatchMiddleware::class);
        $this->pipe(NotFoundHandler::class);
        return $this;
    }

    abstract protected function initPipeline(): void;

    abstract protected function initRoutes(): void;

    protected function createContainer(): ApplicationContainer
    {
        return new ApplicationContainer($this->createConfig());
    }

    protected function createConfig(): ApplicationContainerConfig
    {
        $config = new ApplicationContainerConfig($this->getConfigCacheFile());
        foreach ($this->getConfigProviderList() as $configProvider) {
            $config->addProviderClass($configProvider);
        }
        if ($this->isDevMode()) {
            $config->addProviderClass(DebugConfigProvider::class);
        }
        return $config;
    }

    protected function getConfigProviderList(): array
    {
        return [];
    }

    public function getContainer(): ApplicationContainer
    {
        if (!isset($this->container)) {
            $this->container = $this->createContainer();
        }
        return $this->container;
    }

    public function __debugInfo(): ?array
    {
        return [
            'env' => $this->env
        ];
    }
}
