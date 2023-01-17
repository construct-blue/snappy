<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block;

use Blue\Core\Application\Session\Session;
use Blue\Logic\Block\Block;
use Blue\Logic\Block\BlockRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BlockAddAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');

        $data = $request->getParsedBody();
        $block = new Block();
        if (trim($data['code']) != '') {
            $block->setCode($data['code']);
        }

        $repo = new BlockRepository($snapp);

        if ($block->getCode() && $repo->existsByCode($block->getCode())) {
            /** @var Session $session */
            $session = $request->getAttribute(Session::class);
            $session->addMessage('Block already exists');
        } else {
            $repo->save($block);
        }

        return new Response();
    }
}
