<?php

declare(strict_types=1);

namespace Blue\Core\Http\Uri;

use Mezzio\Helper\UrlHelper;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UriFactoryInterface;

class UriBuilderFactory
{
    public function __invoke(ContainerInterface $container): UriBuilder
    {
        return new UriBuilder($container->get(UriFactoryInterface::class), $container->get(UrlHelper::class));
    }
}
