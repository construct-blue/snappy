<?php

declare(strict_types=1);

namespace Blue\Snapps\System\MyAccount;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Models\User\User;
use Blue\Models\User\UserRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MyAccountAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        /** @var User|null $user */
        $user = $request->getAttribute(User::class);

        if (!empty($data['name']) && !$user?->isAdmin()) {
            $user?->setName($data['name']);
        }

        if (!empty($data['password'])) {
            $user?->setPasswordPlain($data['password']);
        }

        if ($user) {
            UserRepository::instance()->save($user);
        }

        return new Response();
    }
}
