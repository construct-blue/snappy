<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Page;

use Blue\Models\Cms\Page\PageRepository;
use Blue\Snapps\System\Cms\Page\View\PageView;
use Blue\Snapps\System\Cms\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snappCode = $request->getAttribute('snapp');
        $uriBuilder = $this->getUriBuilder($request);
        $this->assign('cmsPath', (string) $uriBuilder->withRoute('pages', ['snapp' => $snappCode]));
        $repo = new PageRepository($snappCode);
        $pages = iterator_to_array($repo->findAll());
        return new HtmlResponse($this->render(PageView::class, ['pages' => $pages]));
    }
}
