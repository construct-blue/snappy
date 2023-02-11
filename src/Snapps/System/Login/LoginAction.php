<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Login;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Models\User\UserRepository;
use Blue\Models\User\UserState;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $session = $this->getSession($request);
        $repo = UserRepository::instance();
        $message = 'Invalid username or password!';

        if (empty($data['token'])) {
            $session->addMessage($message);
            return new Response();
        }
        if (empty($data['username'])) {
            $session->addMessage($message);
            return new Response();
        }
        if (empty($data['password'])) {
            $session->addMessage($message);
            return new Response();
        }

        $token = $data['token'];
        $username = $data['username'];
        $password = $data['password'];

        if (!$session->checkToken($token)) {
            $session->addMessage($message);
            return new Response();
        }
        if (!$repo->existsByName($username)) {
            $session->addMessage($message);
            return new Response();
        }

        $session->renewToken();
        $user = $repo->findByName($username);

        if (!$user->getState()->is(UserState::ACTIVE)) {
            $session->addMessage($message);
            return new Response();
        }

        if (!$user->verifyPassword($password)) {
            $session->addMessage($message);
            return new Response();
        }

        $session->setUserId($user->getId());
        return new Response();
    }
}
