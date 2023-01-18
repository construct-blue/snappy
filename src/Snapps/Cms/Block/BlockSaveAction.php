<?php

namespace Blue\Snapps\Cms\Block;

use Blue\Cms\Block\BlockRepository;
use Blue\Core\View\ViewParser;
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
        if (isset($data['id']) && isset($data['content'])) {
            $repo = new BlockRepository($snapp);
            $block = $repo->findById($data['id']);
            $block->setContent((new ViewParser())->parseString($data['content']));
            if (isset($data['code'])) {
                $block->setCode($data['code']);
            }
            $repo->save($block);
        }

        return new Response();
    }
}
