<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block;

use Blue\Cms\Block\BlockRepository;
use Blue\Snapps\Cms\Block\View\BlockView;
use Blue\Snapps\Cms\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlockHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $repo = new BlockRepository($request->getAttribute('snapp'));
        $blocks = iterator_to_array($repo->findAll());
        return new HtmlResponse(
            $this->render(BlockView::class, ['blocks' => $blocks])
        );
    }
}
