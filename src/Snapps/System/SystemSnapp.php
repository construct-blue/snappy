<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Http\FaviconHandler;
use Blue\Core\Http\PostRedirectGetMiddleware;
use Blue\Models\User\UserPermission;
use Blue\Models\User\UserPermissionMiddleware;
use Blue\Snapps\System\Analytics\Day\DayHandler;
use Blue\Snapps\System\Analytics\Day\DayRefreshAction;
use Blue\Snapps\System\Cms\Block\BlockAddAction;
use Blue\Snapps\System\Cms\Block\BlockDeleteAction;
use Blue\Snapps\System\Cms\Block\BlockHandler;
use Blue\Snapps\System\Cms\Block\BlockSaveAction;
use Blue\Snapps\System\Cms\Page\PageAddAction;
use Blue\Snapps\System\Cms\Page\PageCreateBlocksAction;
use Blue\Snapps\System\Cms\Page\PageDeleteAction;
use Blue\Snapps\System\Cms\Page\PageHandler;
use Blue\Snapps\System\Cms\Page\PageSaveAction;
use Blue\Snapps\System\Login\LoginAction;
use Blue\Snapps\System\Login\LoginHandler;
use Blue\Snapps\System\Logout\LogoutHandler;
use Blue\Snapps\System\MyAccount\MyAccountAction;
use Blue\Snapps\System\MyAccount\MyAccountHandler;
use Blue\Snapps\System\Settings\Tesla\TeslaSetupAction;
use Blue\Snapps\System\Settings\Tesla\TeslaSetupHandler;
use Blue\Snapps\System\Settings\User\UserAddAction;
use Blue\Snapps\System\Settings\User\UserDeleteAction;
use Blue\Snapps\System\Settings\User\UserHandler;
use Blue\Snapps\System\Settings\User\UserSaveAction;
use Blue\Snapps\System\Startpage\StartpageHandler;

class SystemSnapp extends AbstractSnapp
{
    protected function getConfigProviderList(): array
    {
        return [
            \Blue\Core\View\ConfigProvider::class,
            ConfigProvider::class
        ];
    }

    protected function initPipeline(): void
    {
        $this->pipe(UserPermissionMiddleware::class);
        $this->pipe(PostRedirectGetMiddleware::class);
    }

    protected function initRoutes(): void
    {
        FaviconHandler::addRoutes($this, __DIR__ . '/logo.png');

        $this->get('/', StartpageHandler::class, 'start')->setOptions([]);

        $this->get('/login', LoginHandler::class, 'login')->setOptions([]);
        $this->post('/login', LoginAction::class)->setOptions([]);
        $this->get('/logout', LogoutHandler::class, 'logout')->setOptions([]);
        $this->get('/my-account', MyAccountHandler::class, 'account')
            ->setOptions([UserPermission::class => UserPermission::ACCOUNT]);
        $this->post('/my-account', MyAccountAction::class)
            ->setOptions([UserPermission::class => UserPermission::ACCOUNT]);

        $this->get('/settings/users', UserHandler::class, 'settings')
            ->setOptions([UserPermission::class => UserPermission::SETTINGS]);
        $this->post('/settings/users/add', UserAddAction::class)
            ->setOptions([UserPermission::class => UserPermission::SETTINGS]);
        $this->post('/settings/users/save', UserSaveAction::class)
            ->setOptions([UserPermission::class => UserPermission::SETTINGS]);
        $this->post('/settings/users/delete', UserDeleteAction::class)
            ->setOptions([UserPermission::class => UserPermission::SETTINGS]);

        $this->get('/settings/setup/tesla', TeslaSetupHandler::class, 'tesla')
            ->setOptions([UserPermission::class => UserPermission::SETTINGS]);
        $this->post('/settings/setup/tesla', TeslaSetupAction::class)
            ->setOptions([UserPermission::class => UserPermission::SETTINGS]);

        $this->get('/cms/pages[/[{snapp}]]', PageHandler::class, 'pages')
            ->setOptions([UserPermission::class => UserPermission::CMS]);
        $this->post('/cms/pages/delete[/[{snapp}]]', PageDeleteAction::class)
            ->setOptions([UserPermission::class => UserPermission::CMS]);
        $this->post('/cms/pages/add[/[{snapp}]]', PageAddAction::class)
            ->setOptions([UserPermission::class => UserPermission::CMS]);
        $this->post('/cms/pages/save[/[{snapp}]]', PageSaveAction::class)
            ->setOptions([UserPermission::class => UserPermission::CMS]);
        $this->post('/cms/pages/createBlocks[/[{snapp}]]', PageCreateBlocksAction::class)
            ->setOptions([UserPermission::class => UserPermission::CMS]);

        $this->get('/cms/blocks[/[{snapp}]]', BlockHandler::class, 'blocks')
            ->setOptions([UserPermission::class => UserPermission::CMS]);
        $this->post('/cms/blocks/delete[/[{snapp}]]', BlockDeleteAction::class)
            ->setOptions([UserPermission::class => UserPermission::CMS]);
        $this->post('/cms/blocks/add[/[{snapp}]]', BlockAddAction::class)
            ->setOptions([UserPermission::class => UserPermission::CMS]);
        $this->post('/cms/blocks/save[/[{snapp}]]', BlockSaveAction::class)
            ->setOptions([UserPermission::class => UserPermission::CMS]);

        $this->get('/analytics/day[/{code}]', DayHandler::class, 'analytics')
            ->setOptions([UserPermission::class => UserPermission::ANALYTICS]);
        $this->post('/analytics/day/refresh', DayRefreshAction::class)
            ->setOptions([UserPermission::class => UserPermission::ANALYTICS]);
    }
}
