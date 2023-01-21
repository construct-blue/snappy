<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page;

use Blue\Cms\Page\PageRepository;
use Blue\Snapps\Cms\Page\View\PageView;
use Blue\Snapps\Cms\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $repo = new PageRepository($request->getAttribute('snapp'));
        $pages = iterator_to_array($repo->findAll());
        return new HtmlResponse($this->render(PageView::class, ['pages' => $pages]));
    }
}
