<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page;

use Blue\Cms\Page\PageRepository;
use Blue\Core\Application\Handler\ActionHandler;
use Blue\Core\View\ViewParser;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageSaveAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');
        $data = $request->getParsedBody();

        if (isset($data['id'])) {
            $repo = new PageRepository($snapp);
            $page = $repo->findById($data['id']);
            if (isset($data['header'])) {
                $page->setHeader($data['header']);
            }
            if (isset($data['main'])) {
                $page->setMain($data['main']);
            }
            if (isset($data['footer'])) {
                $page->setFooter($data['footer']);
            }
            if (isset($data['code'])) {
                $page->setCode($data['code']);
            }
            if (isset($data['title'])) {
                $page->setTitle($data['title']);
            }
            if (isset($data['description'])) {
                $page->setDescription($data['description']);
            }
            $repo->save($page);
        }

        return new Response();
    }
}
