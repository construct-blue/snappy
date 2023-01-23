<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Page;

use Blue\Models\Cms\Block\BlockCreator;
use Blue\Models\Cms\Block\BlockRepository;
use Blue\Models\Cms\Page\PageRepository;
use Blue\Snapps\System\Cms\ActionHandler;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageCreateBlocksAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');
        $data = $request->getParsedBody();
        if (isset($data['id'])) {
            $repo = new PageRepository($snapp);
            $page = $repo->findById($data['id']);
            $blockCreator = new BlockCreator(new BlockRepository($snapp));
            $created = $blockCreator->createMissing($page->getHeader());
            $created = array_merge($created, $blockCreator->createMissing($page->getMain()));
            $created = array_merge($created, $blockCreator->createMissing($page->getFooter()));
            $created = implode(', ', array_unique($created));
            $this->getSession($request)->addMessage("Blocks created: $created");
        }

        return new Response();
    }
}
