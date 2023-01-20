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
        $page = $request->getAttribute(Page::class);
        $placeholder = new BlockPlaceholder(new BlockRepository($ingressResult->getRoute()->getCode()));
        return new HtmlResponse($placeholder->replace($this->render(PageView::class, ['page' => $page])));
    }
}
