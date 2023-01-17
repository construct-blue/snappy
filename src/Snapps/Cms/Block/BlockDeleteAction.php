<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block;

use Blue\Logic\Block\BlockRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BlockDeleteAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        BlockRepository::instance()->delete($data['id'] ?? '');
        return new Response();
    }
}
