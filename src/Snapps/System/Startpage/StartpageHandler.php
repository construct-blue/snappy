<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Startpage;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Snapps\System\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class StartpageHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $siteSnapps = array_filter($this->getSnapps($request), fn(SnappRoute $route) => $route->isSite());
        $this->assign('siteSnapps', $siteSnapps);
        return new HtmlResponse($this->render(StartpageView::class));
    }
}
