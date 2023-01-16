<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

interface SnappInterface extends MiddlewareInterface
{
    public function run(): void;

    public function pipe($middlewareOrPath, $middleware = null): void;
    public function init(): SnappInterface;
    public function getContainer(): ContainerInterface;
}
