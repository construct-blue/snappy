<?php

declare(strict_types=1);

namespace Blue\Models\Cms\Page\Handler;

use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Application\Snapp\SnappRouteResult;
use Blue\Models\Cms\Block\BlockPlaceholder;
use Blue\Models\Cms\Block\BlockRepository;
use Blue\Models\Cms\Page\Page;
use Blue\Models\Cms\Page\PageRepository;
use Laminas\Diactoros\Response\HtmlResponse;
use ParsedownExtra;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PageHandler extends TemplateHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var SnappRouteResult $ingressResult */
        $ingressResult = $request->getAttribute(SnappRouteResult::class);
        $repo = new PageRepository($ingressResult->getRoute()->getCode());
        $code = $request->getUri()->getPath();
        if ($repo->existsByCode($code)) {
            return $this->handle($request->withAttribute(Page::class, $repo->findByCode($code)));
        }
        return parent::process($request, $handler);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var SnappRouteResult $ingressResult */
        $ingressResult = $request->getAttribute(SnappRouteResult::class);
        /** @var Page $page */
        $page = $request->getAttribute(Page::class);

        $parsedown = new ParsedownExtra();
        $this->assign('title', $page->getTitle());
        $this->assign('description', $page->getDescription());
        $this->assign('header', $parsedown->text($page->getHeader()));
        $this->assign('main', $parsedown->text($page->getMain()));
        $this->assign('footer', $parsedown->text($page->getFooter()));

        $placeholder = new BlockPlaceholder(new BlockRepository($ingressResult->getRoute()->getCode()));
        return new HtmlResponse($placeholder->replace($this->render(PageView::class)));
    }
}
