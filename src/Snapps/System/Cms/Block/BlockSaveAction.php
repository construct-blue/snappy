<?php

namespace Blue\Snapps\System\Cms\Block;

use Blue\Core\Application\Handler\ActionHandler;
use Blue\Models\Cms\Block\BlockRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlockSaveAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');
        $data = $request->getParsedBody();
        if (isset($data['id']) && isset($data['content'])) {
            $repo = new BlockRepository($snapp);
            $block = $repo->findById($data['id']);
            $block->setContent($data['content']);
            if (isset($data['code'])) {
                $block->setCode($data['code']);
            }
            $repo->save($block);
        }

        return new Response();
    }
}
