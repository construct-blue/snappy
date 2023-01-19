<?php

namespace Blue\Snapps\System\User;

use Blue\Core\Application\Handler\ActionHandler;
use Laminas\Diactoros\Response;
use Blue\Core\Authentication\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserDeleteAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        UserRepository::instance()->delete($data['id'] ?? '');
        return new Response();
    }
}
