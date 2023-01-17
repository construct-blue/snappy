<?php

declare(strict_types=1);

namespace Blue\Snapps\Blue\Startpage;

use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Http\Attribute;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class StartpageHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var IngressRoute[] $apps */
        $apps = Attribute::SNAPP_ROUTES->getFrom($request);

        $appLinks = [];
        foreach ($apps as $app) {
            if ($app->getDomain()) {
                $appLinks[(string)$app->getUri()] = $app->getName();
            }
        }

        $this->assign('apps', $appLinks);

        return new HtmlResponse($this->render(StartpageView::class));
    }
}
