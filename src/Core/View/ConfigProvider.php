<?php

namespace Blue\Core\View;

use Mezzio\Template\TemplateRendererInterface;
use Blue\Core\Application\Error\Error;
use Blue\Core\Application\Error\NotFound\NotFound;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'mezzio' => [
                'error_handler' => [
                    'template_404' => NotFound::class,
                    'template_error' => Error::class,
                ]
            ],
            'dependencies' => [
                'aliases' => [
                    TemplateRendererInterface::class => ViewRendererMezzioBridge::class,
                ],
                'factories' => [
                    ViewLogger::class => ViewLoggerFactory::class,
                    ViewRenderer::class => ViewRendererFactory::class,
                    ViewRendererMezzioBridge::class => ViewRendererMezzioBridgeFactory::class,
                ],
            ]
        ];
    }
}
