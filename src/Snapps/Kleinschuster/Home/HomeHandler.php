<?php

namespace Blue\Snapps\Kleinschuster\Home;

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
            $this->assign('page', $repo->findByCode($code));
        }
        return new HtmlResponse($this->render(Home::class));
    }
}
