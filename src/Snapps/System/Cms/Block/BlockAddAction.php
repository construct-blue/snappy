<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Block;

use Blue\Models\Cms\Block\Block;
use Blue\Models\Cms\Block\BlockRepository;
use Blue\Snapps\System\Cms\ActionHandler;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlockAddAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');
        $block = new Block();
        $data = $request->getParsedBody();
        if (trim($data['code']) != '') {
            $block->setCode($data['code']);
        }
        $repo = new BlockRepository($snapp);
        $repo->save($block);

        return new Response();
    }
}
