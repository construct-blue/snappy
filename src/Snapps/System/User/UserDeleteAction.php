<?php

namespace Blue\Snapps\System\User;

use Laminas\Diactoros\Response;
use Blue\Core\Authentication\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserDeleteAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        UserRepository::instance()->delete($data['id'] ?? '');
        return new Response();
    }
}
