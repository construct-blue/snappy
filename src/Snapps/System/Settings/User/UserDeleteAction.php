<?php

namespace Blue\Snapps\System\Settings\User;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Models\User\UserRepository;
use Laminas\Diactoros\Response;
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
