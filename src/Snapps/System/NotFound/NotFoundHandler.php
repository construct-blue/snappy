<?php

declare(strict_types=1);

namespace Blue\Snapps\System\NotFound;

use Blue\Snapps\System\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->render(NotFound::class), 404);
    }
}
