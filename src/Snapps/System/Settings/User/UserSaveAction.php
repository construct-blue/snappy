<?php

namespace Blue\Snapps\System\Settings\User;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Core\Exception\CoreException;
use Blue\Models\User\UserRepository;
use Blue\Models\User\UserRole;
use Blue\Models\User\UserState;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserSaveAction extends ActionHandler
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

        $data['snapps'] = $data['snapps'] ?? [];

        if (!is_array($data['snapps'])) {
            throw new CoreException('snapps not array');
        }

        $user->setSnapps($data['snapps']);

        UserRepository::instance()->save($user);

        return new Response();
    }
}