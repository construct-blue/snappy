<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Closure;
use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

class ApplicationContainerConfig
{
    public function __construct(private readonly ?string $cacheFile = null)
    {
    }

    protected array $providers = [
        \Mezzio\ConfigProvider::class,
        \Mezzio\Router\ConfigProvider::class,
        \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
        \Mezzio\Helper\ConfigProvider::class,
        \Laminas\Diactoros\ConfigProvider::class,
        \Laminas\HttpHandlerRunner\ConfigProvider::class,
        ConfigProvider::class,
        \Blue\Core\Logger\ConfigProvider::class,
        \Blue\Core\Http\ConfigProvider::class,
    ];

    protected array $postProcessors = [];

    public function toArray(): array
    {
        $config = (new ConfigAggregator(
            $this->getProviders(),
            $this->cacheFile,
            $this->postProcessors
        ))->getMergedConfig();
        $dependencies = $config['dependencies'];
        $dependencies['services'] = compact('config');
        return $dependencies;
    }

    protected function getProviders(): array
    {
        return $this->providers;
    }

    public function addProviderClass(string $providerClass): self
    {
        $this->providers[] = $providerClass;
        return $this;
    }

    public function addPostProcessor(Closure $closure): self
    {
        $this->postProcessors[] = $closure;
        return $this;
    }

    public function addPhpFile(string $pattern): self
    {
        $this->providers[] = new PhpFileProvider($pattern);
        return $this;
    }

    public function addArray(array $config): self
    {
        $this->providers[] = new ArrayProvider($config);
        return $this;
    }
}
