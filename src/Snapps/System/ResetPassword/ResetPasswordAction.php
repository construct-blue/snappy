<?php

declare(strict_types=1);

namespace Blue\Snapps\System\ResetPassword;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Models\User\UserException;
use Blue\Models\User\UserRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ResetPasswordAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $token = $request->getAttribute('token');
        $data = $request->getParsedBody();
        $user = UserRepository::instance()->findByName($data['name']);

        if ($user->verifyPasswordResetToken($token)) {
            $user->setPasswordPlain($data['password']);
            $user->invalidatePasswordResetToken();
            UserRepository::instance()->save($user);
            return new Response\RedirectResponse($this->getUriBuilder($request)->withRoute('login')->build());
        }

        throw UserException::forValidation('password', 'invalid reset token');
    }
}