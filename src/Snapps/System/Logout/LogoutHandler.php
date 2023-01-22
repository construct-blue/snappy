<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Logout;

use Blue\Snapps\System\TemplateHandler;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LogoutHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $session = $this->getSession($request);
        $session->setUser(null);
        $session->renewToken();
        $session->save();
        $params = $request->getQueryParams();
        return new RedirectResponse($params['redirect'] ?? '/');
    }
}
