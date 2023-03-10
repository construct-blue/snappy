<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms;

use Blue\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class ActionHandler extends \Blue\Core\Application\Handler\ActionHandler
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var User|null $user */
        $user = $request->getAttribute(User::class);
        $snappCode = $request->getAttribute('snapp');
        if ($snappCode && !$user?->hasSnapp($snappCode)) {
            return $handler->handle($request);
        }
        return parent::process($request, $handler);
    }
}
