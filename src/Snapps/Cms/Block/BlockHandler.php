<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block;

use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\Session\Session;
use Blue\Core\Http\Attribute;
use Blue\Logic\Block\BlockRepository;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlockHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapps = [];
        /** @var IngressRoute $route */
        foreach (Attribute::SNAPP_ROUTES->getFrom($request) as $route) {
            $snapps[$route->getCode()] = $route->getName();
        }

        $snapp = $request->getAttribute('snapp');

        /** @var Session $session */
        $session = $request->getAttribute(Session::class);
        $this->assign('messages', $session->getMessages());
        $session->resetMessages();
        $repo = new BlockRepository($snapp);
        $blocks = iterator_to_array($repo->findAll());
        return new HtmlResponse(
            $this->render(BlockView::class, ['blocks' => $blocks, 'snapp' => $snapp, 'snapps' => $snapps])
        );
    }
}
