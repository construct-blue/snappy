<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block;

use Blue\Cms\Block\BlockRepository;
use Blue\Core\Application\Handler\ActionHandler;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BlockDeleteAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');
        $data = $request->getParsedBody();
        $repo = new BlockRepository($snapp);
        if (isset($data['id'])) {
            $repo->delete($data['id']);
        }
        return new Response();
    }
}
