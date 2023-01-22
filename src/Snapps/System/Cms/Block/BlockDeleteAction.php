<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Block;

use Blue\Snapps\System\Cms\ActionHandler;
use Blue\Models\Cms\Block\BlockRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
