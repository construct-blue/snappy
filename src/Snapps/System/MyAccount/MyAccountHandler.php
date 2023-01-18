<?php

declare(strict_types=1);

namespace Blue\Snapps\System\MyAccount;

use Blue\Core\Application\Handler\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MyAccountHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->getSession($request)->isLoggedIn()) {
            return new RedirectResponse('/');
        }
        return new HtmlResponse($this->render(MyAccountView::class));
    }
}
