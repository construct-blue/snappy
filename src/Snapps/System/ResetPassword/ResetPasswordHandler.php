<?php

declare(strict_types=1);

namespace Blue\Snapps\System\ResetPassword;

use Blue\Core\Application\Handler\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResetPasswordHandler extends TemplateHandler
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->getSession($request)->isLoggedIn()) {
            return $handler->handle($request);
        }

        return parent::process($request, $handler);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->render(ResetPasswordView::class));
    }
}