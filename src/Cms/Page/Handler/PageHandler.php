<?php

declare(strict_types=1);

namespace Blue\Cms\Page\Handler;

use Blue\Cms\Block\BlockPlaceholder;
use Blue\Cms\Block\BlockRepository;
use Blue\Cms\Page\Page;
use Blue\Cms\Page\PageRepository;
use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Application\Ingress\IngressResult;
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
        /** @var IngressResult $ingressResult */
        $ingressResult = $request->getAttribute(IngressResult::class);
        $repo = new PageRepository($ingressResult->getRoute()->getCode());
        $code = $request->getUri()->getPath();
        if ($repo->existsByCode($code)) {
            return $this->handle($request->withAttribute(Page::class, $repo->findByCode($code)));
        }
        return $handler->handle($request);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var IngressResult $ingressResult */
        $ingressResult = $request->getAttribute(IngressResult::class);
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
