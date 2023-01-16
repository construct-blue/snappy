<?php

declare(strict_types=1);

namespace Blue\SnApp\System\User;

use Laminas\Diactoros\Response\HtmlResponse;
use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Application\Session\Session;
use Blue\Core\Authentication\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Session $session */
        $session = $request->getAttribute(Session::class);
        $this->assign('users', iterator_to_array(UserRepository::instance()->findAll()));
        $this->assign('messages', $session->getMessages());
        $session->resetMessages();
        return new HtmlResponse($this->render(UserOverview::class));
    }
}
