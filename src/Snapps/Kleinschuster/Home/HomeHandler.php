<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Cms\Block\BlockPlaceholder;
use Blue\Cms\Block\BlockRepository;
use Blue\Cms\Page\Page;
use Blue\Cms\Page\PageRepository;
use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Application\Ingress\IngressResult;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var IngressResult $ingressResult */
        $ingressResult = $request->getAttribute(IngressResult::class);
        $repo = new PageRepository($ingressResult->getRoute()->getCode());
        $code = $request->getUri()->getPath();
        if ($repo->existsByCode($code)) {
            $page = $repo->findByCode($code);
            $parsedown = new \ParsedownExtra();
            $this->assign('header', $parsedown->text($page->getHeader()));
            $this->assign('main', $parsedown->text($page->getMain()));
            $this->assign('footer', $parsedown->text($page->getFooter()));
        }
        $placeholder = new BlockPlaceholder(new BlockRepository($ingressResult->getRoute()->getCode()));
        return new HtmlResponse($placeholder->replace($this->render(Home::class)));
    }
}
