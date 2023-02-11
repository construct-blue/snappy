<?php

declare(strict_types=1);

namespace Blue\Snapps\System\MyAccount;

use Blue\Core\Http\Header;
use Blue\Models\User\User;
use Blue\Snapps\System\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MyAccountHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::class);
        $this->assign('backUrl', Header::REFERER->getFrom($request, '/'));
        $this->assign('user', $user);
        return new HtmlResponse($this->render(MyAccountView::class));
    }
}
