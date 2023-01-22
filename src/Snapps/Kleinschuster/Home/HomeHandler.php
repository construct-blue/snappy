<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Application\Snapp\SnappRouteResult;
use Blue\Models\Cms\Block\BlockPlaceholder;
use Blue\Models\Cms\Block\BlockRepository;
use Blue\Models\Cms\Page\PageRepository;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var SnappRouteResult $ingressResult */
        $ingressResult = $request->getAttribute(SnappRouteResult::class);
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
