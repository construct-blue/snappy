<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page;

use Blue\Cms\Page\Page;
use Blue\Cms\Page\PageRepository;
use Blue\Core\Application\Handler\ActionHandler;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageAddAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');
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
