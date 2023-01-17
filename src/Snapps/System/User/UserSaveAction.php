<?php

namespace Blue\Snapps\System\User;

use Laminas\Diactoros\Response;
use Blue\Core\Authentication\User;
use Blue\Core\Authentication\UserRepository;
use Blue\Core\Authentication\UserRole;
use Blue\Core\Authentication\UserState;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Exception\CoreException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserSaveAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = UserRepository::instance()->findById($data['id'] ?? '');

        if (isset($data['name'])) {
            $name = strip_tags($data['name']);
            $user->setName($name);
        }

        if (!empty($data['password'])) {
            $user->setPasswordPlain($data['password']);
        }

        if (isset($data['state'])) {
            $user->setState(UserState::from($data['state']));
        }

        $data['roles'] = $data['roles'] ?? [];

        if (!is_array($data['roles'])) {
            throw new CoreException('roles not array');
        }

        $user->setRoles(UserRole::map($data['roles']));

        UserRepository::instance()->save($user);

        return new Response();
    }
}
