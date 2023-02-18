<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Models\User\User;
use Blue\Models\User\UserPermission;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class TemplateHandler extends \Blue\Core\Application\Handler\TemplateHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $this->getSession($request);
        /** @var User|null $user */
        $user = $request->getAttribute(User::class);
        $uriBuilder = $this->getUriBuilder($request);

        $this->assign('session', $this->getSession($request));

        $this->assign('currentPath', (string)$uriBuilder->withMatchedRoutePath());
        $this->assign('isLoggedIn', (string)$session->isLoggedIn());
        $this->assign('activeUserName', (string)$user?->getName());
        $this->assign('startPath', (string)$uriBuilder->withRoute('start'));
        if ($user?->hasPermission(UserPermission::CMS)) {
            $this->assign('cmsPath', (string)$uriBuilder->withRoute('pages'));
        }
        if ($user?->hasPermission(UserPermission::SETTINGS)) {
            $this->assign('settingsPath', (string)$uriBuilder->withRoute('settings'));
        }
        $this->assign('teslaPath', (string)$uriBuilder->withRoute('tesla'));
        $this->assign('myAccountPath', (string)$uriBuilder->withRoute('account'));
        $this->assign('loginPath', (string)$uriBuilder->withRoute('login')->withRedirectParam());
        $this->assign('logoutPath', (string)$uriBuilder->withRoute('logout'));
        return parent::process($request, $handler);
    }
}
