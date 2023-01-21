<?php

declare(strict_types=1);

namespace Blue\Snapps\Blue\MyAccount;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Core\Authentication\UserRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MyAccountAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $session = $this->getSession($request);
        $userId = $session->getUser()->getId();
        $user = UserRepository::instance()->findById($userId);

        if (!empty($data['name'])) {
            $user->setName($data['name']);
        }

        if (!empty($data['password'])) {
            $user->setPasswordPlain($data['password']);
        }

        UserRepository::instance()->save($user);

        $session->getUser()->setName($user->getName());

        return new Response();
    }
}
