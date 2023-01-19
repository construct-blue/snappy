<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page;

use Blue\Cms\Page\PageRepository;
use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Snapps\Cms\Page\View\PageView;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');
        $repo = new PageRepository($snapp);
        $this->assignSnapps($request);
        $pages = iterator_to_array($repo->findAll());
        return new HtmlResponse($this->render(PageView::class, ['pages' => $pages, 'snapp' => $snapp]));
    }
}
