<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Login;

use Blue\Core\Http\Header;
use Blue\Snapps\System\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $session = $this->getSession($request);
        $this->assign('token', $session->getToken());

        $params = $request->getQueryParams();
        $this->assign('backPath', Header::REFERER->getFrom($request, '/'));

        if ($session->isLoggedIn()) {
            return new RedirectResponse($params['redirect'] ?? '/');
        } else {
            return new HtmlResponse($this->render(LoginView::class));
        }
    }
}
