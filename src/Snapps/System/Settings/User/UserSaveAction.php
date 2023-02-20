<?php

namespace Blue\Snapps\System\Settings\User;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Core\Exception\CoreException;
use Blue\Models\User\UserRepository;
use Blue\Models\User\UserRole;
use Blue\Models\User\UserState;
use Blue\Snapps\System\Settings\User\Exception\UserActionException;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserSaveAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $model = UserModel::initFromForm($request->getParsedBody());
        $user = UserRepository::instance()->findById($model->getId());

        $model->updateUser($user);

        UserRepository::instance()->save($user);

        return new Response();
    }
}
