<?php

namespace Blue\SnApp\Cms\Block;

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
        $data = $request->getParsedBody();
        if (isset($data['content']) && isset($data['id'])) {
            $block = BlockRepository::instance()->findById($data['id']);
            $block->setContent((new ViewParser())->parseString($data['content']));
            $block->setCode($data['code']);
            BlockRepository::instance()->save($block);
        }
        return new Response();
    }
}
