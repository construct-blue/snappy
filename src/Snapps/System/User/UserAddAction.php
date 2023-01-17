<?php

namespace Blue\Snapps\System\User;

use Laminas\Diactoros\Response;
use Blue\Core\Application\Session\Session;
use Blue\Core\Authentication\User;
use Blue\Core\Authentication\UserRepository;
use Blue\Core\Database\ObjectStorage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserAddAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = new User();
        $name = strip_tags($data['name'] ?? '');
        $user->setName($name);

        if (UserRepository::instance()->existsByName($user->getName())) {
            /** @var Session $session */
            $session = $request->getAttribute(Session::class);
            $session->addMessage('user already exists');
        } else {
            UserRepository::instance()->save($user);
        }

        return new Response();
    }
}
