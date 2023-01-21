<?php

declare(strict_types=1);

namespace Blue\Snapps\Settings\User;

use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Authentication\UserRepository;
use Blue\Snapps\Settings\User\View\UserView;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assign('users', iterator_to_array(UserRepository::instance()->findAll()));
        return new HtmlResponse($this->render(UserView::class));
    }
}
