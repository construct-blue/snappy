<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block;

use Blue\Cms\Block\Block;
use Blue\Cms\Block\BlockRepository;
use Blue\Core\Application\Session\Session;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BlockAddAction implements RequestHandlerInterface
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
