<?php

namespace Blue\Snapps\System\Settings\User;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Models\User\User;
use Blue\Models\User\UserRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserAddAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = new User();
        $name = strip_tags($data['name'] ?? '');
        $user->setName($name);

        UserRepository::instance()->save($user);


        return new Response();
    }
}
