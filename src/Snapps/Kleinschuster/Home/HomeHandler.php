<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Core\Application\Ingress\IngressResult;
use Laminas\Diactoros\Response\HtmlResponse;
use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Logic\Block\BlockRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var IngressResult $ingressResult */
        $ingressResult = $request->getAttribute(IngressResult::class);
        $route = $ingressResult->getRoute();
        $repo = new BlockRepository($route->getCode());
        $this->assign('blocks', iterator_to_array($repo->findAll()));
        return new HtmlResponse($this->render(Home::class));
    }
}
