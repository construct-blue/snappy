<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Blue\Core\Environment\Environment;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\Application;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Mezzio\Router\RouteCollectorInterface;
use Blue\Core\Application\Debug\ConfigProvider as DebugConfigProvider;
use Throwable;

abstract class AbstractSnapp extends Application implements SnappInterface
{
    private ApplicationContainer $container;
    public Environment $config;

    final private function __construct()
    {
        try {
            parent::__construct(
                $this->getContainer()->get(MiddlewareFactory::class),
                $this->getContainer()->get(MiddlewarePipeInterface::class),
                $this->getContainer()->get(RouteCollectorInterface::class),
                $this->getContainer()->get(RequestHandlerRunnerInterface::class)
            );
        } catch (Throwable $exception) {
            ob_start();
            include 'public/error.html';
            $html = ob_get_clean();
            echo str_replace('<error/>', "<pre>{$exception->getMessage()}</pre>", $html);
            exit;
        }
    }

    /**
     * @return SnappProxy<static>
     */
    public static function default(): SnappInterface
    {
        /** @phpstan-ignore-next-line */
        return new SnappProxy(fn() => new static());
    }


    final public function init(): static
    {
        $this->initRoutes();
        $this->pipe(ErrorHandler::class);
        $this->pipe(RouteMiddleware::class);
        $this->pipe(UrlHelperMiddleware::class);
        $this->pipe(ServerUrlMiddleware::class);
        $this->initPipeline();
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
        $config = new ApplicationContainerConfig('cache' . DIRECTORY_SEPARATOR . md5(static::class) . '.config.cache');
        foreach ($this->getConfigProviderList() as $configProvider) {
            $config->addProviderClass($configProvider);
        }
        $config->addArray([
            'router' => [
                'fastroute' => [
                    'cache_enabled' => true,
                    'cache_file' => 'cache' . DIRECTORY_SEPARATOR . md5(static::class) . '.router.cache'
                ],
            ],
        ]);
        if (Environment::instance()->isDevMode()) {
            opcache_reset();
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
        return [];
    }
}
