<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\Snapps\System\Cms\Block\BlockHandler;
use Blue\Snapps\System\Cms\Page\PageHandler;
use Blue\Snapps\System\Login\LoginHandler;
use Blue\Snapps\System\Logout\LogoutHandler;
use Blue\Snapps\System\MyAccount\MyAccountHandler;
use Blue\Snapps\System\NotFound\NotFound;
use Blue\Snapps\System\NotFound\NotFoundHandler;
use Blue\Snapps\System\Settings\Tesla\TeslaSetupHandler;
use Blue\Snapps\System\Settings\User\UserHandler;
use Blue\Snapps\System\Startpage\StartpageHandler;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'mezzio' => [
                'error_handler' => [
                    'template_404' => NotFound::class,
                ],
            ],
            'dependencies' => [
                'aliases' => [
                    \Mezzio\Handler\NotFoundHandler::class => NotFoundHandler::class
                ],
                'factories' => [
                    NotFoundHandler::class => TemplateHandlerFactory::class,
                    StartpageHandler::class => TemplateHandlerFactory::class,
                    LoginHandler::class => TemplateHandlerFactory::class,
                    LogoutHandler::class => TemplateHandlerFactory::class,
                    MyAccountHandler::class => TemplateHandlerFactory::class,
                    BlockHandler::class => TemplateHandlerFactory::class,
                    PageHandler::class => TemplateHandlerFactory::class,
                    UserHandler::class => TemplateHandlerFactory::class,
                    TeslaSetupHandler::class => TemplateHandlerFactory::class,
                ],
            ]
        ];
    }
}
