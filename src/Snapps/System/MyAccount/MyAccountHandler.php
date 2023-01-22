<?php

declare(strict_types=1);

namespace Blue\Snapps\System\MyAccount;

use Blue\Snapps\System\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MyAccountHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assign('user', $this->getSession($request)->getUser());
        return new HtmlResponse($this->render(MyAccountView::class));
    }
}
