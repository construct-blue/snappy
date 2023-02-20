<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\User;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Models\User\UserRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserResetPasswordAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = UserRepository::instance()->findById($data['id'] ?? '');
        $token = $user->generatePasswordResetToken();
        $uri = $this->getUriBuilder($request)
            ->withCurrentUri()
            ->withRoute('reset_password', ['token' => $token])->build();
        mail($user->getName(), 'Reset Password', "$uri");
        UserRepository::instance()->save($user);
        return new Response();
    }
}