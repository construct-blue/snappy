<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Page;

use Blue\Snapps\System\Cms\ActionHandler;
use Blue\Models\Cms\Block\Block;
use Blue\Models\Cms\Block\BlockRepository;
use Blue\Models\Cms\Page\Page;
use Blue\Models\Cms\Page\PageRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageAddAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');

        $blockRepo = new BlockRepository($snapp);

        if (!$blockRepo->existsByCode('header')) {
            $blockRepo->save((new Block())->setCode('header'));
            $this->getSession($request)->addMessage('Header block added');
        }
        if (!$blockRepo->existsByCode('footer')) {
            $blockRepo->save((new Block())->setCode('footer'));
            $this->getSession($request)->addMessage('Footer block added');
        }

        $page = new Page();
        $data = $request->getParsedBody();
        if (trim($data['code']) != '') {
            $page->setCode($data['code']);
        }
        $repo = new PageRepository($snapp);
        $repo->save($page);

        return new Response();
    }
}
