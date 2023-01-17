<?php

namespace Blue\Snapps\Cms\Block;

use Blue\Core\View\ViewParser;
use Blue\Logic\Block\BlockRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BlockSaveAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');

        $data = $request->getParsedBody();
        if (isset($data['content']) && isset($data['id'])) {
            $repo = new BlockRepository($snapp);
            $block = $repo->findById($data['id']);
            $block->setContent((new ViewParser())->parseString($data['content']));
            $block->setCode($data['code']);
            $repo->save($block);
        }
        return new Response();
    }
}
